<?php

namespace App\Validator\Application;

use App\Validator\ValidationResult;
use App\Validator\ValidatorInterface;

class PassportExpirationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $res = new ValidationResult();

        if (isset($data['passport_expiration'])) {
            try {
                new \DateTimeImmutable($data['passport_expiration']);
            } catch (\Throwable $e) {
                $res->addError('passport_expiration must be a valid date (YYYY-MM-DD).');
            }
        }
        return $res;
    }
}
