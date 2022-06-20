<?php


namespace App\Services\BaseServices\SizeService\Contracts;


interface SizeValidatorContract
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
