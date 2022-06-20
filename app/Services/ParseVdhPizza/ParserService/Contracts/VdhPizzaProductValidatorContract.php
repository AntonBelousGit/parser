<?php
declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ParserService\Contracts;


interface VdhPizzaProductValidatorContract
{
    /**
     * Validate data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     */
    public function validate(array $data, array $rules = []): array;
}
