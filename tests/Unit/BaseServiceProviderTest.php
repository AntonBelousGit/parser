<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

abstract class BaseServiceProviderTest extends TestCase
{
    protected array $services;

    /**
     * @return void
     */
    public function testContainerReturnsExpectedImplementationByContract(): void
    {
        foreach ($this->services as $contract => $implementation) {
            $this->assertInstanceOf(
                $implementation,
                app($contract)
            );
        }
    }
}
