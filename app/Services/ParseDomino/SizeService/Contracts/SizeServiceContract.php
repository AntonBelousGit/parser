<?php
declare(strict_types=1);


namespace App\Services\ParseDomino\SizeService\Contracts;


interface SizeServiceContract
{
    /**
     * @param array $array
     * @return bool
     */
    public function update(array $array = []): bool;
}
