<?php
declare(strict_types=1);


namespace App\Services\ToppingService\Contracts;


interface ToppingServiceContract
{
    public function store(array $array = []);

    public function update(array $array = []);
}
