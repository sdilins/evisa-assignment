<?php

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

/**
 * @extends ServiceEntityRepository<Application>
 */

class ApplicationRepository extends ServiceEntityRepository
{
    private HtmlSanitizerInterface $htmlSanitizer;

    public function __construct(
        ManagerRegistry $registry,
        HtmlSanitizerInterface $htmlSanitizer
    ){
        parent::__construct($registry, Application::class);
        $this->htmlSanitizer = $htmlSanitizer;
    }

    public function findOneByPassport(string $passportNumber): ?Application
    {
        return $this->findOneBy(['passportNumber' => $passportNumber]);
    }

    public function createApplication(array $data): Application
    {
        $data = array_map(fn($item) => $this->htmlSanitizer->sanitize((string)$item), $data);

        try {
            $application = new Application();

            $application->setPassportNumber($data['passport_number']);
            $application->setFirstName($data['first_name']);
            $application->setLastName($data['last_name']);
            $application->setCitizenship($data['citizenship']);
            $application->setPassportExpiration(new \DateTimeImmutable($data['passport_expiration']));
            $application->setStatus($this->getRandomStatus());
            $application->setCreatedAt(new \DateTimeImmutable());
            $application->setUpdatedAt(new \DateTimeImmutable());

            $this->save($application, true);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to create application: ' . $e->getMessage());
        }
    }

    public function save(Application $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function getRandomStatus(): string
    {
        //TODO: replace with real status logic
        $statuses = ['pending', 'processing', 'approved', 'denied'];
        return $statuses[array_rand($statuses)];
    }
}
