<?php
declare(strict_types=1);


namespace App\Services\FlavorService\Contracts;


interface FlavorServiceContract
{
    public function store(array $array = []);

    public function update(array $array = []);
}
