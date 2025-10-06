<?php

namespace App\Validator\Application;

use App\Validator\ValidationResult;
use App\Validator\ValidatorInterface;

class PassportFormatValidator implements ValidatorInterface
{
    private string $pattern = '/^[A-Z0-9\-]{3,64}$/i';

    public function validate(array $data): ValidationResult
    {
        $res = new ValidationResult();
        if (isset($data['passport_number'])) {
            $passport = trim((string)$data['passport_number']);
            if (!preg_match($this->pattern, $passport)) {
                $res->addError('passport_number has invalid format (allowed: letters, numbers, dash, 3-64 chars).');
            }
        }

        return $res;
    }
}
