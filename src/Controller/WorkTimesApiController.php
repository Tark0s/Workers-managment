<?php

namespace App\Controller;

use App\Entity\WorkTime;
use App\Repository\WorkerRepository;
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
}
