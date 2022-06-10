<?php
declare(strict_types=1);


namespace App\Services\SizeService\Contracts;


interface SizeServiceContract
{
    /**
     * @param array $array
     * @return mixed
     */
    public function update(array $array = []): mixed;
}
