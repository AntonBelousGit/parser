<?php

declare(strict_types=1);

namespace App\Services\ParseDomino\ProductService;

use App\Services\BaseValidator;
use App\Services\ParseDomino\ProductService\Contracts\ProductValidatorContract;
use App\Services\ParserManager\Exception\InvalidConfigDataException;
use Throwable;

class ProductValidator extends BaseValidator implements ProductValidatorContract
{
    /**
     * Validate port data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws InvalidConfigDataException|Throwable
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
            'image' => ['required', 'array','min:1'],
            'image.*' => ['required'],
            'image_mobile' => ['required', 'array','min:1'],
            'image_mobile.*' => ['required'],
            'toppings.*.id' => ['required','string','max:50'],
            'toppings.*.name' => ['required','string','max:200'],
            'sizes.*.id' => ['required','string','max:50'],
            'sizes.*.name' => ['required','string','max:200'],
            'sizes.*.flavors.*.id' => ['required','string','max:50'],
            'sizes.*.flavors.*.name' => ['required','string','max:200'],
            'sizes.*.flavors.*.product.price' => ['required','integer'],
        ];
    }

    /**
     * Size data validation exception.
     *
     */
    protected function getValidationException(): InvalidConfigDataException
    {
        return new InvalidConfigDataException('Size data is invalid. Check ports source.');
    }
}
