<?php
declare(strict_types=1);

namespace Tests\Unit\BaseServices\FlavorService;

use App\Models\Flavor;
use App\Services\BaseServices\AttributeDTO;
use App\Services\BaseServices\FlavorService\FlavorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlavorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFlavor()
    {
        $flavor = $this->getTestAttribute()->flavor;
        $service = $this->app->make(FlavorService::class);
        $service->updateOrCreate($flavor);

        $searchFlavor = Flavor::find($flavor[0]['id']);
        $this->assertEquals($flavor[0]['name'], $searchFlavor->name);
    }

    public function testUpdateFlavor()
    {
        $flavor = $this->getTestAttribute()->flavor;
        $service = $this->app->make(FlavorService::class);
        $service->updateOrCreate($flavor);
        $searchFlavor = Flavor::find($flavor[0]['id']);
        $this->assertEquals($flavor[0]['name'], $searchFlavor->name);

        $flavor_update = $this->updateTestAttribute()->flavor;
        $service->updateOrCreate($flavor_update);
        $searchUpdateFlavor = Flavor::find($flavor_update[0]['id']);
        $this->assertEquals($flavor_update[0]['name'], $searchUpdateFlavor->name);
        $this->assertNotEquals($flavor[0]['name'], $searchUpdateFlavor->name);
    }

    /**
     * @return AttributeDTO
     */
    protected function getTestAttribute(): AttributeDTO
    {
        return new AttributeDTO(
            flavor: [
            [
                "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                "name" => "Стандартне",
                "code" => "USA"
            ],
            [
                "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                "name" => "Тонке",
                "code" => "ITAL"
            ]
        ],
        );
    }

    protected function updateTestAttribute(): AttributeDTO
    {
        return new AttributeDTO(
            flavor: [
            [
                "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                "name" => "СтандартнеXXX",
                "code" => "UA"
            ]
        ],
        );
    }
}
