<?php

declare(strict_types=1);

namespace App\Services\BaseServices\ToppingService;

use App\Services\BaseValidator;
use App\Services\BaseServices\ToppingService\Contracts\ToppingValidatorContract;
use App\Services\BaseServices\ToppingService\Exception\InvalidToppingDataException;

class ToppingValidator extends BaseValidator implements ToppingValidatorContract
{
    /**
     * Validate port data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws InvalidToppingDataException
     */
    public function validate(array $data, array $rules = []): array
    {
        if ($rules === []) {
            $rules = $this->getValidationRules();
        }

        return parent::validate($data, $rules);
    }

    /**
     * Port data validation rules.
     *
     * @return string[][]
     */
    protected function getValidationRules(): array
    {
        return [
            'id' => ['required','string','max:50'],
            'name' => ['required', 'string','max:100'],
        ];
    }

    /**
     * Size data validation exception.
     * @return InvalidToppingDataException
     */
    protected function getValidationException(): InvalidToppingDataException
    {
        return new InvalidToppingDataException('Size data is invalid. Check ports source.');
    }
}
