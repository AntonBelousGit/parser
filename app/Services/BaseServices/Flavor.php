<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Flavor
{
    public function __construct(
        public array $flavor= [],
    ) {
    }
}
