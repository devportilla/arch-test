<?php

namespace Ulatest\Test\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\DBAL\Driver\Connection;
use PHPUnit_Framework_Assert;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
use Ulatest\App\AppKernel;
use Ulatest\App\Commands\UserSubscribe;
use Ulatest\Domain\Subscriber\SubscriberIdGenerator;
use Ulatest\Domain\Subscriber\SubscriberRepository;
use Ulatest\Test\Stub\SubscriberEmailStub;
use Ulatest\Test\Stub\SubscriberIdStub;
use Ulatest\Test\Stub\SubscriberStub;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\first;
use function Lambdish\Phunctional\map;

class ConsoleCommandContext implements Context
{
    private $application;
    /** @var AppKernel */
    private $kernel;
    /** @var CommandTester */
    private $tester;
    private $repository;

    public function __construct(
        MessageBus $commandBus,
        SubscriberIdGenerator $idGenerator,
        SubscriberRepository $repository
    ) {
        $this->kernel      = new AppKernel('test', false);
        $this->application = new Application($this->kernel);
        $this->application->add(new UserSubscribe($commandBus, $idGenerator));
        $this->repository = $repository;
    }

    /** @BeforeScenario */
    public function Boot()
    {
        $this->kernel->boot();
    }

    /** @AfterScenario */
    public function clearAllTables()
    {
        $connection = $this->getService('doctrine.orm.entity_manager')->getConnection();

        $tables            = $this->tables($connection);
        $truncateTablesSql = $this->truncateDatabaseSql($tables);

        $connection->exec($truncateTablesSql);
    }

    /** @return Container */
    protected function getSymfonyContainer()
    {
        return $this->kernel->getContainer();
    }

    protected function getService($id)
    {
        return $this->getSymfonyContainer()->get($id);
    }

    private function truncateDatabaseSql(array $tables)
    {
        $truncateTables = map($this->truncateTableSql(), $tables);

        return sprintf('SET FOREIGN_KEY_CHECKS = 0; %s SET FOREIGN_KEY_CHECKS = 1;', implode(' ', $truncateTables));
    }

    private function truncateTableSql()
    {
        return function (array $table) {
            return sprintf('TRUNCATE TABLE `%s`;', first($table));
        };
    }

    private function tables(Connection $connection)
    {
        return $connection->query('show tables')->fetchAll();
    }

    /**
     * @Given there are subscribers:
     */
    public function thereAreUserAlias(TableNode $subscribers)
    {
        each($this->registerSubscriber(), $subscribers);
    }

    private function registerSubscriber()
    {
        return function ($subscriberData) {
            $subscriberId    = SubscriberIdStub::create($subscriberData['id']);
            $subscriberEmail = SubscriberEmailStub::create($subscriberData['email']);
            $subscriber      = SubscriberStub::create($subscriberId, $subscriberEmail);
            $this->repository->save($subscriber);

            return $subscriber;
        };
    }

    /**
     * @When /^I run '([^"]*)' command without arguments$/
     */
    public function iRunCommand($name)
    {
        $command      = $this->application->find($name);
        $this->tester = new CommandTester($command);
        $this->tester->execute(['command' => $command->getName()]);
    }

    /**
     * @When /^I run '([^"]*)' command with email '([^"]*)'$/
     */
    public function iRunCommandWithArgument($name, $argument)
    {
        $command      = $this->application->find($name);
        $this->tester = new CommandTester($command);
        $this->tester->execute(['command' => $command->getName(), 'email' => $argument]);
    }

    /**
     * @Then I should see
     */
    public function iShouldSee(PyStringNode $result)
    {
        PHPUnit_Framework_Assert::assertSame($result->getRaw(), $this->tester->getDisplay());
    }

    /**
     * @Then /^The command exit code should be (\d+)$/
     */
    public function theExitCodeShouldBe($exitCode)
    {
        PHPUnit_Framework_Assert::assertSame((int)$exitCode, $this->tester->getStatusCode());
    }
}
