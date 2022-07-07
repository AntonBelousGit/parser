<?php
declare(strict_types=1);


namespace App\Services\StoreManager\Contracts;

interface ProductDriverContract
{
    /**
     * Save products into DB
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void;
}
