<?php
declare(strict_types=1);

namespace App\Services\StoreManager\Contracts;

interface ConfigValidatorContract
{
    /**
     * Validate product data.
     *
     * @param array $data
     * @param array $rules
     * @return array
     */
    public function validate(array $data, array $rules = []): array;
}
