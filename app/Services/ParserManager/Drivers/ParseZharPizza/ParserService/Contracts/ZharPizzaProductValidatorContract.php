<?php
declare(strict_types=1);

namespace App\Services\ParserManager\Drivers\ParseZharPizza\ParserService\Contracts;


interface ZharPizzaProductValidatorContract
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
