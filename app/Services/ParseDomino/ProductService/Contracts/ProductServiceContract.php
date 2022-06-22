<?php
declare(strict_types=1);


namespace App\Services\ParseDomino\ProductService\Contracts;


interface ProductServiceContract
{
    /**
     * Save products into DB
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void;
}
