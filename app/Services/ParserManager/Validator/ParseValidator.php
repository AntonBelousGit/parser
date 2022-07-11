<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Validator;

use App\Services\BaseValidator;
use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\Exception\InvalidParseDataException;
use Throwable;

class ParseValidator extends BaseValidator implements ParseValidatorContract
{
    /**
     * Validate port data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws Throwable
     */
    public function validate(array $data, array $rules): array
    {
        return parent::validate($data, $rules);
    }

    /**
     *Data validation exception.
     *
     */
    protected function getValidationException(): InvalidParseDataException
    {
        return new InvalidParseDataException('Parse data is invalid');
    }
}
