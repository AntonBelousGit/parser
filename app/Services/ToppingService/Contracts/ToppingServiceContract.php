<?php
declare(strict_types=1);


namespace App\Services\ToppingService\Contracts;


interface ToppingServiceContract
{
    /**
     * @param array $array
     * @return bool
     */
    public function update(array $array = []): bool;
}
