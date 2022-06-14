<?php
declare(strict_types=1);

namespace Tests\Unit\SizeService;

use App\Models\Size;
use App\Services\ParserService\Attribute;
use App\Services\SizeService\SizeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SizeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateSize()
    {
        $size = $this->getTestAttribute()->size;
        $service = $this->app->make(SizeService::class);
        $service->update($size);
        $searchSize = Size::find($size[0]['id']);
        $this->assertEquals($size[0]['name'], $searchSize->name);
    }

    public function testUpdateSize()
    {
        $size = $this->getTestAttribute()->size;
        $service = $this->app->make(SizeService::class);
        $service->update($size);
        $searchSize = Size::find($size[0]['id']);
        $this->assertEquals($size[0]['name'], $searchSize->name);

        $sizeUpdate = $this->updateTestAttribute()->size;
        $service->update($sizeUpdate);
        $searchUpdateSize = Size::find($sizeUpdate[0]['id']);
        $this->assertEquals($sizeUpdate[0]['name'], $searchUpdateSize->name);
        $this->assertNotEquals($size[0]['name'], $searchUpdateSize->name);
    }

    /**
     * @return Attribute
     */
    protected function getTestAttribute(): Attribute
    {
        return new Attribute(
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

    protected function updateTestAttribute(): Attribute
    {
        return new Attribute(
            size: [
            [
                "id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                "name" => "Велика XXXXL",
            ]
        ],
        );
    }
}