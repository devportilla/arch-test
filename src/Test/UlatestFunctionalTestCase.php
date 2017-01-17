<?php

namespace Ulatest\Test;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Kernel;
use Ulatest\App\AppKernel;
use function Lambdish\Phunctional\first;
use function Lambdish\Phunctional\map;

class UlatestFunctionalTestCase extends UlatestUnitTestCase
{
    /** @var Kernel */
    private $kernel;

    protected function setUp()
    {
        $this->getKernel()->boot();
        $this->clearAllTables();
    }

    protected function tearDown()
    {
        $this->getKernel()->shutdown();
    }

    protected function getSubscriberRepository()
    {
        return $this->getService('subscriber_repository');
    }

    protected function getKernel()
    {
        return $this->kernel = $this->kernel ?: new AppKernel($environment = 'test', $debug = true);
    }

    /** @return Container */
    protected function getSymfonyContainer()
    {
        return $this->getKernel()->getContainer();
    }

    protected function getService($id)
    {
        return $this->getSymfonyContainer()->get($id);
    }

    protected function clearUnitOfWork()
    {
        $this->getService('doctrine.orm.entity_manager')->clear();
    }

    private function clearAllTables()
    {
        $connection = $this->getService('doctrine.orm.entity_manager')->getConnection();

        $tables            = $this->tables($connection);
        $truncateTablesSql = $this->truncateDatabaseSql($tables);

        $connection->exec($truncateTablesSql);
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
}
