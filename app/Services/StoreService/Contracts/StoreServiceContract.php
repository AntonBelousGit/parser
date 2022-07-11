<?php
declare(strict_types=1);

namespace App\Services\StoreService\Contracts;

interface StoreServiceContract
{
    /**
     *  Store in DB new parsed data or update
     *
     * @param array $data
     * @return void
     */
    public function store(array $data): void;
}
