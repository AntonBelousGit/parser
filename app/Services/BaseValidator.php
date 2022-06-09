<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Validation\ValidationException;
use Throwable;

abstract class BaseValidator
{
    /**
     * @param ValidationFactory $validationFactory
     */
    public function __construct(protected  ValidationFactory $validationFactory)
    {
    }

    /**
     * Validate data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws Throwable
     */
    public function validate(array $data, array $rules): array
    {
        try {
            return
                $this->validationFactory
                    ->make($data, $rules)
                    ->stopOnFirstFailure()
                    ->validate();
        } catch (ValidationException $exception) {
            throw ($this->getValidationException() ?? $exception);
        }
    }

    /**
     * Return validation exception.
     *
     * @return Throwable|null
     */
    abstract protected function getValidationException(): ?Throwable;
}
