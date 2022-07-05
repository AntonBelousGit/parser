<?php
declare(strict_types=1);

namespace Tests\Unit\BaseServices\SizeService;

use App\Models\Size;
use App\Services\BaseServices\AttributeDTO;
use App\Services\BaseServices\SizeService\SizeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SizeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateSize()
    {
        $size = $this->getTestAttribute()->size;
        $service = $this->app->make(SizeService::class);
        $service->updateOrCreate($size);
        $searchSize = Size::find($size[0]['id']);
        $this->assertEquals($size[0]['name'], $searchSize->name);
    }

    public function testUpdateSize()
    {
        $size = $this->getTestAttribute()->size;
        $service = $this->app->make(SizeService::class);
        $service->updateOrCreate($size);
        $searchSize = Size::find($size[0]['id']);
        $this->assertEquals($size[0]['name'], $searchSize->name);

        $sizeUpdate = $this->updateTestAttribute()->size;
        $service->updateOrCreate($sizeUpdate);
        $searchUpdateSize = Size::find($sizeUpdate[0]['id']);
        $this->assertEquals($sizeUpdate[0]['name'], $searchUpdateSize->name);
        $this->assertNotEquals($size[0]['name'], $searchUpdateSize->name);
    }

    /**
     * @return AttributeDTO
     */
    protected function getTestAttribute(): AttributeDTO
    {
        return new AttributeDTO(
            size: [
            [
                "id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                "name" => "Стандартна",

            ],
            [
                "id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                "name" => "Велика",

            ]
        ],
        );
    }

    protected function updateTestAttribute(): AttributeDTO
    {
        return new AttributeDTO(
            size: [
            [
                "id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                "name" => "Велика XXXXL",
            ]
        ],
        );
    }
}
