<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Validator;

use App\Services\BaseValidator;

use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Exception\InvalidConfigDataException;
use Illuminate\Validation\Factory as ValidationFactory;
use Throwable;

class ConfigValidator extends BaseValidator implements ConfigValidatorContract
{
    protected ConnectToParseService $directory;
    public function __construct(ValidationFactory $validationFactory)
    {
        parent::__construct($validationFactory);
        $this->directory = new ConnectToParseService();
    }

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
            'method' => [
                function ($attribute, $value, $fail) {
                    if (!method_exists($this->directory, $value)) {
                        $fail('The ' . $attribute . ' is invalid.');
                    }
                }
                ],
            'url' => ['required', 'string'],
        ];
    }

    /**
     * Size data validation exception.
     *
     */
    protected function getValidationException(): InvalidConfigDataException
    {
        return new InvalidConfigDataException('Config "parsers.php" is invalid.');
    }
}
