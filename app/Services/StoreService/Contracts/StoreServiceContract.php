<?php

declare(strict_types=1);

namespace App\Services\StoreService\Contracts;

use App\Services\ParserManager\DTOs\ParserProductDataDTO;

interface StoreServiceContract
{
    /**
     *  Store in DB new parsed data or update
     *
     * @param ParserProductDataDTO $data
     * @return void
     */
    public function store(ParserProductDataDTO $data): void;
}
