<?php
declare(strict_types=1);


namespace App\Services\SizeService\Contracts;


interface SizeServiceContract
{
    public function store(array $array = []);

    public function update(array $array = []);
}
