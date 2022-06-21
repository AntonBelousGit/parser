<?php
declare(strict_types=1);


namespace App\Services\BaseServices\ToppingService\Contracts;


interface ToppingServiceContract
{
    /**
     * @param array $array
     * @return bool
     */
    public function updateOrCreate(array $array = []): bool;
}
