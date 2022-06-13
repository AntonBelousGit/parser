<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommandPizzaUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test a console command.
     *
     * @return void
     */
    public function test_console_command_pizza_update_successful()
    {
        $this->artisan('pizza:update')->assertSuccessful();
    }
}
