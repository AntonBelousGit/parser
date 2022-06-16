<?php

declare(strict_types=1);

namespace App\Services\ParseDomino\SizeService;

use App\Services\BaseValidator;
use App\Services\ParseDomino\SizeService\Contracts\SizeValidatorContract;
use App\Services\ParseDomino\SizeService\Exception\InvalidSizeDataException;
use Throwable;

class SizeValidator extends BaseValidator implements SizeValidatorContract
{
    /**
     * Validate port data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws InvalidSizeDataException|Throwable
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
    protected function getValidationException(): InvalidSizeDataException
    {
        return new InvalidSizeDataException('Size data is invalid. Check ports source.');
    }
}
