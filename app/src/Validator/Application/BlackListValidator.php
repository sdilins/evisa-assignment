<?php

namespace App\Validator\Application;

use App\Validator\ValidationResult;
use App\Validator\ValidatorInterface;
use App\Repository\BlackListApplicationRepository;

class BlackListValidator implements ValidatorInterface
{
    public function __construct(
        private BlackListApplicationRepository $blacklistRepo
    ) {
    }

    public function validate(array $data): ValidationResult
    {
        $res = new ValidationResult();
        if (!empty($data['passport_number'])) {
            $passport = trim((string)$data['passport_number']);
            if ($this->blacklistRepo->findOneByPassport($passport)) {
                $res->addError('passport_number is blacklisted.');
            }
        }
        return $res;
    }
}
