<?php
declare(strict_types=1);


namespace App\Services\BaseServices\SizeService\Contracts;


interface SizeServiceContract
{
    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void;
}
