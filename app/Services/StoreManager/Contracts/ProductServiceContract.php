<?php
declare(strict_types=1);

namespace App\Services\StoreManager\Contracts;

interface ProductServiceContract
{
    /**
     *  Store in DB new parsed data or update
     *  @return void
     */
    public function parse(): void;
}
