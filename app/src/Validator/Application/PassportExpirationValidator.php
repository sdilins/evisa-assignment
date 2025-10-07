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
                $expiration = new \DateTimeImmutable($data['passport_expiration']);

                $sixMonthsFromNow = (new \DateTimeImmutable())->modify('+6 months');

                if ($expiration <= $sixMonthsFromNow) {
                    $res->addError('passport_expiration must be at least 6(six) months beyond.');
                }
            } catch (\Throwable $e) {
                $res->addError('passport_expiration must be a valid date (YYYY-MM-DD).');
            }
        } else {
            $res->addError('passport_expiration is required.');
        }

        return $res;
    }
}
