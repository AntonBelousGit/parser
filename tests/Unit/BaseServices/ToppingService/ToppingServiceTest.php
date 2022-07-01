<?php
declare(strict_types=1);

namespace Tests\Unit\BaseServices\ToppingService;

use App\Models\Topping;
use App\Services\BaseServices\Attribute;
use App\Services\BaseServices\ToppingService\ToppingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToppingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateTopping()
    {
        $topping = $this->getTestAttribute()->topping;
        $service = $this->app->make(ToppingService::class);
        $service->updateOrCreate($topping);
        $searchTopping = Topping::find($topping[0]['id']);
        $this->assertEquals($topping[0]['name'], $searchTopping->name);
    }

    public function testUpdateTopping()
    {
        $topping = $this->getTestAttribute()->topping;
        $service = $this->app->make(ToppingService::class);
        $service->updateOrCreate($topping);
        $searchTopping = Topping::find($topping[0]['id']);
        $this->assertEquals($topping[0]['name'], $searchTopping->name);

        $toppingUpdate = $this->updateTestAttribute()->topping;
        $service->updateOrCreate($toppingUpdate);
        $searchUpdateTopping = Topping::find($toppingUpdate[0]['id']);
        $this->assertEquals($toppingUpdate[0]['name'], $searchUpdateTopping->name);
        $this->assertNotEquals($topping[0]['name'], $searchUpdateTopping->name);
    }

    /**
     * @return Attribute
     */
    protected function getTestAttribute(): Attribute
    {
        return new Attribute(
            topping: [
            [
                "id" => "57b9883e-7652-4590-9316-f45f2da2cad4",
                "name" => "Cоус Domino's",

            ],
            [
                "id" => "fa4b63ee-d0d0-4a6f-b2ff-4f39dbeb0342",
                "name" => "Шинка",

            ]
        ],
        );
    }

    protected function updateTestAttribute(): Attribute
    {
        return new Attribute(
            topping: [
            [
                "id" => "57b9883e-7652-4590-9316-f45f2da2cad4",
                "name" => "Cоус demi-glace",
            ]
        ],
        );
    }
}
