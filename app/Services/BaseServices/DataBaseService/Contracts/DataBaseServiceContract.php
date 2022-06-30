<?php
declare(strict_types=1);


namespace App\Services\BaseServices\DataBaseService\Contracts;

interface DataBaseServiceContract
{
    /**
     * Save products into DB
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void;
}
