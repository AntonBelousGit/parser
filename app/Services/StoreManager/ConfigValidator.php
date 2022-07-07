<?php

declare(strict_types=1);

namespace App\Services\StoreManager;

use App\Services\BaseValidator;

use App\Services\ParserManager\Exception\InvalidConfigDataException;
use App\Services\StoreManager\Contracts\ConfigValidatorContract;
use Throwable;

class ConfigValidator extends BaseValidator implements ConfigValidatorContract
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
     * @return array
     */
    protected function getValidationRules(): array
    {
        return [
            'parser' => ['required', 'string',
                function ($attribute, $value, $fail) {
                    if (!class_exists($value)) {
                        $fail('The ' . $attribute . ' is invalid.');
                    }
                }],
            'config' => ['required', 'array'],
            'config.address' => ['required', 'string'],
            'config.attribute' => ['required', 'array', 'min:1'],
            'config.attribute.*' => ['required',
                function ($attribute, $value, $fail) {
                    if (!class_exists($value)) {
                        $fail('The ' . $attribute . ' is invalid.');
                    }
                }],
        ];
    }

    /**
     * Size data validation exception.
     *
     */
    protected function getValidationException(): InvalidConfigDataException
    {
        return new InvalidConfigDataException('Config "parsers.php" is invalid. Check ports source.');
    }
}
