<?php

namespace App\Validator\Application;

use App\Validator\ValidationResult;
use App\Validator\ValidatorInterface;

class RequiredFieldsValidator implements ValidatorInterface
{
    /** @var string[] */
    private array $required = [
        'passport_number',
        'first_name',
        'last_name',
        'citizenship',
        'passport_expiration'
    ];

    public function validate(array $data): ValidationResult
    {
        $result = new ValidationResult();

        foreach ($this->required as $field) {
            if (!isset($data[$field]) || $data[$field] === '' || $data[$field] === null) {
                $result->addError("Field '{$field}' is required.");
            }
        }

        return $result;
    }
}
