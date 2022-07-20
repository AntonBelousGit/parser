<?php
declare(strict_types=1);

namespace App\Services\TestDataService\Contracts;

interface TestDataServiceContract
{
    /**
     * Test data
     *
     * @return void
     */
    public function test(): void;
}
