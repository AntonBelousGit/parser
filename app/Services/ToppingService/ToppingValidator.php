<?php

declare(strict_types=1);

namespace App\Services\ToppingService;

use App\Services\BaseValidator;
use App\Services\ToppingService\Contracts\ToppingValidatorContract;
use App\Services\ToppingService\Exception\InvalidToppingDataException;
use Throwable;

class ToppingValidator extends BaseValidator implements ToppingValidatorContract
{
    /**
     * Validate port data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws InvalidToppingDataException|Throwable
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
            'name' => ['required', 'string'],
        ];
    }

    /**
     * Size data validation exception.
     *
     */
    protected function getValidationException(): InvalidToppingDataException
    {
        return new InvalidToppingDataException('Size data is invalid. Check ports source.');
    }
}
