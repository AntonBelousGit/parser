<?php

declare(strict_types=1);

namespace App\Services\ParseZharPizza\ProductService;

use App\Services\BaseValidator;
use App\Services\ParseZharPizza\ProductService\Contracts\ProductValidatorContract;
use App\Services\ParseZharPizza\ProductService\Exception\InvalidProductDataException;
use Throwable;

class ProductValidator extends BaseValidator implements ProductValidatorContract
{
    /**
     * Validate port data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws InvalidProductDataException|Throwable
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
            'name' => ['required', 'string','max:200'],
            'image' => ['required', 'array'],

        ];
    }

    /**
     * Size data validation exception.
     *
     */
    protected function getValidationException(): InvalidProductDataException
    {
        return new InvalidProductDataException('Size data is invalid. Check ports source.');
    }
}
