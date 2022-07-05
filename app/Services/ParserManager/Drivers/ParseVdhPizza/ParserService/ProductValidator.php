<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers\ParseVdhPizza\ParserService;

use App\Services\BaseValidator;
use App\Services\ParserManager\Drivers\ParseVdhPizza\ParserService\Contracts\VdhPizzaProductValidatorContract;
use App\Services\ParserManager\Drivers\ParseVdhPizza\ParserService\Exception\InvalidProductDataException;
use Throwable;

class ProductValidator extends BaseValidator implements VdhPizzaProductValidatorContract
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
            'uid' => ['required','string','max:50'],
            'title' => ['required', 'string'],
            'price' => ['required', 'string'],
            'descr' => ['required', 'string'],
            'gallery' => ['required', 'string'],
        ];
    }

    /**
     * Size data validation exception.
     *
     */
    protected function getValidationException(): InvalidProductDataException
    {
        return new InvalidProductDataException('Product VdhPizza data is invalid. Check ports source.');
    }
}
