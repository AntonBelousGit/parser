<?php
declare(strict_types=1);

namespace App\Services\ParseZharPizza\ParserService\Contracts;


interface ZharPizzaProductValidatorContract
{
    /**
     * Validate rate data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     */
    public function validate(array $data, array $rules = []): array;
}
