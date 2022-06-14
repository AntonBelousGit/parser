<?php
declare(strict_types=1);

namespace Tests\Unit\FlavorService;

use App\Models\Flavor;
use App\Services\FlavorService\FlavorService;
use App\Services\ParserService\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlavorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFlavor()
    {
        $flavor = $this->getTestAttribute()->productRelation;
        $service = $this->app->make(FlavorService::class);
        $service->update($flavor);

        $searchFlavor = Flavor::find($flavor[0]['id']);
        $this->assertEquals($flavor[0]['name'], $searchFlavor->name);
    }

    public function testUpdateFlavor()
    {
        $flavor = $this->getTestAttribute()->productRelation;
        $service = $this->app->make(FlavorService::class);
        $service->update($flavor);
        $searchFlavor = Flavor::find($flavor[0]['id']);
        $this->assertEquals($flavor[0]['name'], $searchFlavor->name);

        $flavor_update = $this->updateTestAttribute()->productRelation;
        $service->update($flavor_update);
        $searchUpdateFlavor = Flavor::find($flavor_update[0]['id']);
        $this->assertEquals($flavor_update[0]['name'], $searchUpdateFlavor->name);
        $this->assertNotEquals($flavor[0]['name'], $searchUpdateFlavor->name);
    }

    /**
     * @return Attribute
     */
    protected function getTestAttribute(): Attribute
    {
        return new Attribute(
            productRelation: [
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

    protected function updateTestAttribute(): Attribute
    {
        return new Attribute(
            productRelation: [
            [
                "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                "name" => "СтандартнеXXX",
                "code" => "UA"
            ]
        ],
        );
    }
}
