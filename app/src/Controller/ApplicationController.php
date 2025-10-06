<?php

namespace App\Controller;

use App\Entity\Application;
use App\Repository\ApplicationRepository;
use App\Service\Application\ValidationChain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ApplicationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidationChain $validationChain,
        private ApplicationRepository $appRepo
    ) {
    }

    #[Route('/applications', name: 'applications_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $result = $this->validationChain->validate($data);
        if (!$result->isValid()) {
            return $this->json(['errors' => $result->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $passport = trim((string)$data['passport_number']);
        $firstName = trim((string)$data['first_name']);
        $lastName = trim((string)$data['last_name']);
        $citizenship = strtoupper(trim((string)$data['citizenship']));
        $passportExp = new \DateTimeImmutable($data['passport_expiration']);

        $application = new Application(
            $passport,
            $firstName,
            $lastName,
            $citizenship,
            $passportExp,
            'pending'
        );

        $this->em->persist($application);
        $this->em->flush();

        return $this->json([
            'passport_number' => $application->getPassportNumber(),
            'status' => $application->getStatus(),
            'created_at' => $application->getCreatedAt()->format(\DateTime::ATOM),
        ], Response::HTTP_CREATED);
    }

    #[Route('/applications/{passport}', name: 'applications_get', methods: ['GET'])]
    public function get(string $passport): JsonResponse
    {
        $passport = trim($passport);
        $application = $this->appRepo->findOneByPassport($passport);

        if (!$application) {
            return $this->json(['error' => 'Application not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'passport_number' => $application->getPassportNumber(),
            'status' => $application->getStatus(),
            'first_name' => $application->getFirstName(),
            'last_name' => $application->getLastName(),
            'citizenship' => $application->getCitizenship(),
            'passport_expiration' => $application->getPassportExpiration()->format('Y-m-d'),
            'created_at' => $application->getCreatedAt()->format(\DateTime::ATOM),
        ], Response::HTTP_OK);
    }

    private function createApplication(array $data): Application
    {
        $application = new Application();
        $application->setPassportNumber(trim((string)$data['passport_number']));
        $application->setFirstName(trim((string)$data['first_name']));
        $application->setLastName(trim((string)$data['last_name']));
        $application->setCitizenship(strtoupper(trim((string)$data['citizenship'])));
        $application->setPassportExpiration(new \DateTimeImmutable($data['passport_expiration']));
        $application->setStatus('pending');

        return $application;
    }
}
