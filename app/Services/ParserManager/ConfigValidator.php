<?php

declare(strict_types=1);

namespace App\Services\ParserManager;

use App\Services\BaseValidator;

use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Exception\InvalidConfigDataException;
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
