<?php

namespace App\Repository;

use App\Entity\Worker;
use App\Entity\WorkTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class WorkTimeRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct($registry, WorkTime::class);
    }

//    TODO fix query
//    public function findByYearAndMonthAndWorker(int $year, int $month, Worker $worker)
//    {
//        $qb = $this->entityManager->createQueryBuilder();
//
//        $qb->select('e')
//            ->where('strftime(\'%Y\', e.yourDateField) = :year')
//            ->andWhere('strftime(\'%m\', e.yourDateField) = :month')
//            ->andWhere('e.worker = :worker')
//            ->setParameter('year', $year)
//            ->setParameter('month', $month)
//            ->setParameter('worker', $worker);
//
//        dd($qb->getQuery());
//
//        return $qb->getQuery()->getResult();
//    }
//
//
//
    public function findByYearAndMonthAndWorker(int $year, int $month, string $workerId)
    {
        $sql = "
            SELECT *
            FROM work_time 
            WHERE YEAR(day) = '$year' 
            AND MONTH(day) = '$month' 
            AND worker_id = '$workerId'
        ";

        $connection = $this->entityManager->getConnection();
        $stmt = $connection->executeQuery($sql);

        return $stmt->fetchAll();
    }
}
