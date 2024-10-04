<?php

namespace App\Controller;

use App\Entity\WorkTime;
use App\Repository\WorkerRepository;
use App\Repository\WorkTimeRepository;
use App\Service\WorkTimeService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/work-times')]
class WorkTimesApiController extends AbstractController
{
    #[Route(name: 'app_work_time_api_create', methods: ['POST'])]
    public function create(Request $request, WorkTimeService $workTimeService, WorkerRepository $workerRepository, LoggerInterface $logger): Response
    {
        $data = json_decode($request->getContent(), true);

        $worker = $workerRepository->find($data['workerId']);

        $startDateTime = new \DateTime($data['startDateTime']);
        $endDateTime = new \DateTime($data['endDateTime']);

        $workTime = new WorkTime($worker, $startDateTime, $endDateTime);

        try {
            $workTimeService->save($workTime);
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
            return new JsonResponse(['error' => 'An error occurred while saving the employee'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Working time has been added!'], Response::HTTP_CREATED);
    }

    #[Route(name: 'app_work_time_api_summary', methods: ['GET'])]
    public function summary(
        Request $request,
        WorkTimeRepository $workTimeRepository,
        WorkerRepository $workerRepository,
        LoggerInterface $logger
    ): Response
    {
        $data = json_decode($request->getContent(), true);

        $dateLength = strlen($data['date']);

        $worker = $workerRepository->find($data['workerId']);

        $hourlyRate = (int) $this->getParameter('hourly_rate');
        $overtimeHourlyRate = $hourlyRate * $this->getParameter('overtime_multiplier');

        switch ($dateLength){
            case 10:
                $date = \DateTime::createFromFormat('d.m.Y', $data['date']);

                $workTime = $workTimeRepository->findOneBy(['worker' => $worker, 'day' => $date]);
                $workHours = $workTime->getWorkHours();
                $total = $workHours * $hourlyRate;

                return new JsonResponse([
                    'total after recalculation' => sprintf("%d %s", $total, $this->getParameter('currency')),
                    'number of hours of a given day' => $workHours,
                    'hourly rate' => $hourlyRate,
                ], Response::HTTP_OK);

            case 7:
                $date = \DateTime::createFromFormat('m.Y', $data['date']);

//                TODO
                $workTimes = $workTimeRepository->findByYearAndMonthAndWorker($date->format('Y'), $date->format('m'), $worker->getId());


                return new JsonResponse([
                    'hourly rate' => $hourlyRate,
                    'overtime hourly rate' => $overtimeHourlyRate ,
//                    // TODO
//                    'number of normal hours of a given month' => "TODO",
                ], Response::HTTP_OK);

            default:
                return new JsonResponse([
                    'message' => 'error'
                ], Response::HTTP_BAD_GATEWAY);

        }
    }
}
