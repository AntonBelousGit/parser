<?php

declare(strict_types=1);

namespace App\Services\StoreService\Validator;

use App\Services\BaseValidator;
use App\Services\StoreService\Contracts\AttributeValidatorContract;
use App\Services\StoreService\Exception\InvalidStoreServiceDataException;
use Throwable;

class AttributeValidator extends BaseValidator implements AttributeValidatorContract
{
    /**
     * Validate port data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws InvalidStoreServiceDataException|Throwable
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
        ];
    }

    /**
     * Size data validation exception.
     *
     */
    protected function getValidationException(): InvalidStoreServiceDataException
    {
        return new InvalidStoreServiceDataException('Attribute data is invalid.');
    }
}
