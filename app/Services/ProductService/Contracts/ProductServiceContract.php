<?php
declare(strict_types=1);


namespace App\Services\ProductService\Contracts;


interface ProductServiceContract
{
    public function store(array $array = []);

    public function update(array $array = []);
}
