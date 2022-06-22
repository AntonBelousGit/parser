<?php
declare(strict_types=1);


namespace App\Services\ParseZharPizza\ProductService\Contracts;


interface ProductServiceContract
{
    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void;
}
