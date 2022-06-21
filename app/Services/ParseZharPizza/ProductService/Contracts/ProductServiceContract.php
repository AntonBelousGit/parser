<?php
declare(strict_types=1);


namespace App\Services\ParseZharPizza\ProductService\Contracts;


interface ProductServiceContract
{
    /**
     * @param array $array
     * @return bool
     */
    public function updateOrCreate(array $array = []): bool;
}
