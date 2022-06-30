<?php

declare(strict_types=1);

namespace App\Services\BaseServices\FlavorService;

use App\Services\BaseServices\FlavorService\Contracts\FlavorValidatorContract;
use App\Services\BaseServices\FlavorService\Exception\InvalidFlavorDataException;
use App\Services\BaseValidator;

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
            'name' => ['required', 'string','max:200'],
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
