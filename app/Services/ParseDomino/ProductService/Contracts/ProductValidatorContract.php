<?php
declare(strict_types=1);

namespace App\Services\ParseDomino\ProductService\Contracts;


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
