<?php


namespace App\Services\ParseDomino\FlavorService\Contracts;


interface FlavorValidatorContract
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