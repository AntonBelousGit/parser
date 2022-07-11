<?php
declare(strict_types=1);

namespace App\Services\StoreService\Contracts;

interface AttributeValidatorContract
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
