<?php
declare(strict_types=1);

namespace App\Services\ParserManager\Drivers\ParseDomino\Contracts;


interface ProductValidatorContract
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
