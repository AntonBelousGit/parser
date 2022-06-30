<?php
declare(strict_types=1);


namespace App\Services\BaseServices\FlavorService\Contracts;

interface FlavorServiceContract
{
    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void;
}
