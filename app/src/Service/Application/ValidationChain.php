<?php

namespace App\Service\Application;

use App\Validator\ValidationResult;
use App\Validator\ValidatorInterface;

class ValidationChain
{
    /**
     * @param ValidatorInterface[] $validators
     */
    public function __construct(
        private iterable $validators
    ) {
    }

    public function validate(array $data): ValidationResult
    {
        $combined = new ValidationResult();

        foreach ($this->validators as $validator) {
            $result = $validator->validate($data);

            if (!$result->isValid()) {
                foreach ($result->getErrors() as $err) {
                    $combined->addError($err);
                }
                break;
            }
        }

        return $combined;
    }
}
