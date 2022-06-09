<?php


namespace App\Services\SizeService\Contracts;


interface SizeValidatorContract
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
