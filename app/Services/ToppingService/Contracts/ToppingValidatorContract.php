<?php


namespace App\Services\ToppingService\Contracts;


interface ToppingValidatorContract
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
