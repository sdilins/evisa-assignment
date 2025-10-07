<?php

namespace App\Controller;

use App\Repository\ApplicationRepository;
use App\Service\Application\ValidationChain;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ApplicationController extends AbstractController
{
    public function __construct(
        private ValidationChain $validationChain,
        private ApplicationRepository $applicationRepository
    ) {
    }

    #[Route('/applications', name: 'applications_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        //TODO: Implement rate limiting to prevent abuse

        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $result = $this->validationChain->validate($data);
        if (!$result->isValid()) {
            return $this->json(['errors' => $result->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $application = $this->applicationRepository->createApplication($data);

        return $this->json([
            'passport_number' => $application->getPassportNumber(),
            'status' => $application->getStatus(),
            'created_at' => $application->getCreatedAt()->format(\DateTime::ATOM),
        ], Response::HTTP_OK);
    }

    #[Route('/applications/{passport}', name: 'applications_get', methods: ['GET'])]
    public function get(string $passport): JsonResponse
    {
        //TODO: Implement API authorization to access control & protect sensitive data

        $passport = trim($passport);
        $application = $this->applicationRepository->findOneByPassport($passport);
        if (!$application) {
            return $this->json(['error' => 'Application not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => $application->getStatus(),
        ], Response::HTTP_OK);
    }
}
