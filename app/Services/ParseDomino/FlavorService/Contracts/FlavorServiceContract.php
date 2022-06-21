<?php
declare(strict_types=1);


namespace App\Services\ParseDomino\FlavorService\Contracts;


interface FlavorServiceContract
{
    /**
     * @param array $array
     * @return bool
     */
    public function updateOrCreate(array $array = []): bool;
}
