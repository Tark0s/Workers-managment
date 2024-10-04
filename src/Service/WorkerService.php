<?php

namespace App\Service;

use App\Entity\Worker;
use Doctrine\ORM\EntityManagerInterface;

class WorkerService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function save( Worker $worker): void
    {
        $this->entityManager->persist($worker);
        $this->entityManager->flush();
    }
}