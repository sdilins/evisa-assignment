<?php

namespace App\Validator\Application;

use App\Repository\ApplicationRepository;
use App\Validator\ValidationResult;
use App\Validator\ValidatorInterface;

class UniquePassportValidator implements ValidatorInterface
{
    public function __construct(
        private ApplicationRepository $applicationRepo
    ) {
    }

    public function validate(array $data): ValidationResult
    {
        $res = new ValidationResult();

        if (!empty($data['passport_number'])) {
            $passport = trim((string)$data['passport_number']);
            if ($this->applicationRepo->findOneByPassport($passport)) {
                $res->addError('An application with this passport_number already exists.');
            }
        }

        return $res;
    }
}
