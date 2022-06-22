<?php
declare(strict_types=1);


namespace App\Services\ParseVdhPizza\ProductService\Contracts;


interface ProductServiceContract
{
    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void;
}
