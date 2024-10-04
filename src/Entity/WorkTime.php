<?php

namespace App\Entity;

use App\Repository\WorkTimeRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: WorkTimeRepository::class)]
class WorkTime
{
    public function __construct(
        Worker $worker,
        DateTimeInterface $startDate,
        DateTimeInterface $endDate
    )
    {
        $this->worker = $worker;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->day = \DateTime::createFromFormat('Y-m-d', $startDate->format('Y-m-d'));
    }

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: Worker::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Worker $worker;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $startDate;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $endDate;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $day;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getWorker(): Worker
    {
        return $this->worker;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function getDay(): \DateTimeInterface
    {
        return $this->day;
    }
}
