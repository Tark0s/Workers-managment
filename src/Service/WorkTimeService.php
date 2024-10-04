<?php

namespace App\Service;

use App\Entity\WorkTime;
use Doctrine\ORM\EntityManagerInterface;

class WorkTimeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function save(WorkTime $worker): void
    {
        $this->entityManager->persist($worker);
        $this->entityManager->flush();
    }
}