<?php
declare(strict_types=1);

namespace App\Services\StoreManager\Contracts;

use App\Services\ParserManager\DTOs\AttributeDTO;

interface AttributeDriverContract
{
    /** Store or Update pizza attribute (size, flavor, topping)
     * @param AttributeDTO $attribute
     * @param array $config
     * @return void
     */
    public function updateOrCreate(AttributeDTO $attribute, array $config): void;
}
