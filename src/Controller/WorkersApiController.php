<?php

namespace App\Controller;

use App\Entity\Worker;
use App\Service\WorkerService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/workers')]
class WorkersApiController extends AbstractController
{
    #[Route(name: 'app_workers_api_create', methods: ['POST'])]
    public function create(Request $request, WorkerService $workerService, LoggerInterface $logger): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['surname'])) {
            $worker = new Worker();
        } else {
            $worker = new Worker(sprintf("%s %s", $data['name'], $data['surname']));
        }

        try {
            $workerService->save($worker);
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
            return new JsonResponse(['error' => 'An error occurred while saving the employee'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['id' => $worker->getId()], Response::HTTP_CREATED);
    }
}
