<?php

namespace Ulatest\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Ulatest\Domain\Subscriber\Subscriber;
use Ulatest\Domain\Subscriber\SubscriberEmail;
use Ulatest\Domain\Subscriber\SubscriberRepository;

final class SubscriberRepositoryMySql implements SubscriberRepository
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Subscriber $subscriber)
    {
        $this->entityManager->persist($subscriber);
        $this->entityManager->flush();
    }

    public function searchByEmail(SubscriberEmail $email)
    {
        return $this->entityManager->getRepository(Subscriber::class)->findOneBy(
            [
                'email' => $email->value(),
            ]
        );
    }
}
