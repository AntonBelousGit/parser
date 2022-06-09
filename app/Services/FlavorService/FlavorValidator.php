<?php

declare(strict_types=1);

namespace App\Services\FlavorService;

use App\Services\BaseValidator;
use App\Services\FlavorService\Contracts\FlavorValidatorContract;
use App\Services\FlavorService\Exception\InvalidFlavorDataException;
use Throwable;

class FlavorValidator extends BaseValidator implements FlavorValidatorContract
{
    /**
     * Validate port data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws InvalidFlavorDataException|Throwable
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
            'code' => ['required', 'string'],
        ];
    }

    /**
     * Size data validation exception.
     *
     */
    protected function getValidationException(): InvalidFlavorDataException
    {
        return new InvalidFlavorDataException('Size data is invalid. Check ports source.');
    }
}
