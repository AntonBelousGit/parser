<?php
declare(strict_types=1);


namespace App\Services\ParseDomino\ProductService\Contracts;


interface ProductServiceContract
{
    /**
     * @param array $array
     * @return bool
     */
    public function update(array $array = []): bool;
}
