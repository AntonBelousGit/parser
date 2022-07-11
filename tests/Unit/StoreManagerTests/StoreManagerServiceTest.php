<?php
declare(strict_types=1);

namespace StoreManagerTests;

use App\Models\Attribute;
use App\Models\Flavor;
use App\Models\History;
use App\Models\Product;
use App\Models\Size;
use App\Models\Topping;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\FlavorDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use App\Services\StoreService\Drivers\AttributeDriver\AttributeDriver;
use App\Services\StoreService\Drivers\ProductDriver\ProductDriver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreManagerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateProduct()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductDriver::class);
        $service->updateOrCreate($productData);
        $searchProduct = Product::find($productData[0]->id);
        $this->assertEquals($productData[0]->name, $searchProduct->name);
    }

    public function testNewProductCheckTopping()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductDriver::class);
        $service->updateOrCreate($productData);
        $searchProduct = Product::find($productData[0]->id);
        $this->assertEquals($productData[0]->name, $searchProduct->name);
        $this->assertContains('Соус Альфредо', $searchProduct->topping->where('id', '841ed46e-fae5-4df5-aabc-6a98155afe2f')->pluck('name'));
    }

    public function testNewProductCheckSize()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductDriver::class);
        $service->updateOrCreate($productData);
        $searchProduct = Product::with('topping')->find($productData[0]->id);
        $findAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(180, $findAttribute->price);
    }

    public function testUpdateSizeFlavorPrice()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductDriver::class);
        $service->updateOrCreate($productData);
        $searchProduct = Product::with('topping')->find($productData[0]->id);
        $findAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(180, $findAttribute->price);

        $updateProductData = $this->updateTestProductData();
        $service->updateOrCreate($updateProductData);
        $findNewAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(255.0, $findNewAttribute->price);
        $this->assertNotEquals(255.0, $findAttribute->price);
    }


    public function testCheckHistoryUpdateSizeFlavorPrice()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductDriver::class);
        $service->updateOrCreate($productData);
        $searchProduct = Product::with('topping')->find($productData[0]->id);
        $findAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(180, $findAttribute->price);

        $updateProductData = $this->updateTestProductData();
        $service->updateOrCreate($updateProductData);
        $findNewAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(255.0, $findNewAttribute->price);
        $this->assertNotEquals(255.0, $findAttribute->price);

        $history = History::where(['historical_type' => 'App\Models\Attribute', 'historical_id' => $findNewAttribute->id])->orderBy('id', 'desc')->first();

        $this->assertEquals(180, $history->changed_value_from);
        $this->assertEquals(255, $history->changed_value_to);
    }

    public function testCreateAttribute()
    {
        $attribute = $this->getTestAttribute();
        $service = $this->app->make(AttributeDriver::class);
        $service->updateOrCreate($attribute);
        $searchFlavor = Flavor::find($attribute->flavor[0]['id']);
        $this->assertEquals($attribute->flavor[0]['name'], $searchFlavor->name);
        $searchSize = Size::find($attribute->size[0]['id']);
        $this->assertEquals($attribute->size[0]['name'], $searchSize->name);
        $searchTopping = Topping::find($attribute->topping[0]['id']);
        $this->assertEquals($attribute->topping[0]['name'], $searchTopping->name);
    }

    public function testUpdateFlavor()
    {
        $attribute = $this->getTestAttribute();
        $service = $this->app->make(AttributeDriver::class);
        $service->updateOrCreate($attribute);
        $searchFlavor = Flavor::find($attribute->flavor[0]['id']);
        $this->assertEquals($attribute->flavor[0]['name'], $searchFlavor->name);
        $searchSize = Size::find($attribute->size[0]['id']);
        $this->assertEquals($attribute->size[0]['name'], $searchSize->name);
        $searchTopping = Topping::find($attribute->topping[0]['id']);
        $this->assertEquals($attribute->topping[0]['name'], $searchTopping->name);

        $attributeUpdate = $this->updateTestAttribute();
        $service->updateOrCreate($attributeUpdate);
        $searchUpdateFlavor = Flavor::find($attributeUpdate->flavor[0]['id']);
        $this->assertEquals($attributeUpdate->flavor[0]['name'], $searchUpdateFlavor->name);
        $this->assertNotEquals($attribute->flavor[0]['name'], $searchUpdateFlavor->name);
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
                "name" => "Стандартна"
            ],
            [
                "id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                "name" => "Велика"
            ]
            ],
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
            ],
            ],
            topping: [
            [
                "id" => "841ed46e-fae5-4df5-aabc-6a98155afe2f",
                "name" => "Соус Альфредо",
             ],
            [
                "id" => "0d0b0510-9520-44a8-9947-2efd2c0f0504",
                "name" => "Моцарела",
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
                "name" => "СтандартнаXXX"
            ],
            [
                "id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                "name" => "Велика"
            ]
        ],
            flavor: [
            [
                "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                "name" => "СтандартнеXXX",
                "code" => "USA"
            ],
            [
                "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                "name" => "ТонкеXXX",
                "code" => "ITAL"
            ],
        ],
            topping: [
            [
                "id" => "841ed46e-fae5-4df5-aabc-6a98155afe2f",
                "name" => "Соус АльфредоXXX",
            ],
            [
                "id" => "0d0b0510-9520-44a8-9947-2efd2c0f0504",
                "name" => "МоцарелаXXX",
            ]
        ],
        );
    }

    /**
     * @return array
     */
    protected function getTestProductData(): array
    {
        return [
            new ProductDTO(
                id: "4d06072b-81b0-4108-9e3a-0cab1fdaf8a4",
                name: "ПІца Мангеттен",
                image: [
                2300 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-2300x2300-70.jpg",
                1150 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-1150x1150-70.jpg",
                "full" => "https://media.dominos.ua/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min.jpg",
                480 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-480x480-70.jpg",
                960 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-960x960-70.jpg"
            ],
                imageMobile: [
                2300 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-2300x2300-70.jpg",
                1150 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-1150x1150-70.jpg",
                "full" => "https://media.dominos.ua/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min.jpg",
                480 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-480x480-70.jpg",
                960 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-960x960-70.jpg",
            ],
                topping: new ToppingDTO(
                    topping: [
                    [
                        "id" => "841ed46e-fae5-4df5-aabc-6a98155afe2f",
                        "name" => "Соус Альфредо",
                    ],
                    [
                        "id" => "0d0b0510-9520-44a8-9947-2efd2c0f0504",
                        "name" => "Моцарела",
                    ],
                    [
                        "id" => "aea741b2-1847-4519-9f26-d598b69f1bb9",
                        "name" => "Гриби",
                    ],
                    [
                        "id" => "311f1e0e-6ecc-4d36-8340-87bcdcb4c4e1",
                        "name" => "Пепероні",
                    ]]
                ),
                sizes: new SizeDTO(
                    size: [
                    [
                        "id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                        "name" => "Стандартна"],
                    ["id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                        "name" => "Велика"]
                ]
                ),
                flavors: new FlavorDTO(
                    flavor: [
                    [
                        "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                        "name" => "Стандартне",
                    ],
                    [
                        "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                        "name" => "Тонке",
                    ],
                    [
                        "id" => "924937c7-1637-4162-aac0-8284f71173a7",
                        "name" => "Борт Філадельфія",
                    ],
                    [
                        "id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                        "name" => "Борт Хот-Дог"
                    ],
                    [
                        "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                        "name" => "Стандартне"
                    ],
                    [
                        "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                        "name" => "Тонке"
                    ],
                    [
                        "id" => "924937c7-1637-4162-aac0-8284f71173a7",
                        "name" => "Борт Філадельфія"
                    ],
                    [
                        "id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                        "name" => "Борт Хот-Дог"
                    ]
                ]
                ),
                attribute: new ProductSizeDTO(
                    attribute: [
                        [
                            "size_id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "flavor_id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                            "price" => 180.0
                        ],
                        [
                            "size_id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "flavor_id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                            "price" => 180.0
                        ],
                        [
                            "size_id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "flavor_id" => "924937c7-1637-4162-aac0-8284f71173a7",
                            "price" => 215.0
                        ],
                        [
                            "size_id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "flavor_id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                            "price" => 230.0
                        ],
                        [
                            "size_id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "flavor_id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                            "price" => 212.0
                        ],
                        [
                            "size_id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "flavor_id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                            "price" => 212.0
                        ],
                        [
                            "size_id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "flavor_id" => "924937c7-1637-4162-aac0-8284f71173a7",
                            "price" => 257.0
                        ],
                        [
                            "size_id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "flavor_id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                            "price" => 272.0
                        ]
                    ]
                )
            )
        ];
    }

    /**
     * @return array
     */
    protected function updateTestProductData(): array
    {
        return [
            new ProductDTO(
                id: "4d06072b-81b0-4108-9e3a-0cab1fdaf8a4",
                name: "ПІца Мангеттен",
                image: [
                2300 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-2300x2300-70.jpg",
                1150 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-1150x1150-70.jpg",
                "full" => "https://media.dominos.ua/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min.jpg",
                480 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-480x480-70.jpg",
                960 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-960x960-70.jpg"
            ],
                imageMobile: [
                2300 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-2300x2300-70.jpg",
                1150 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-1150x1150-70.jpg",
                "full" => "https://media.dominos.ua/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min.jpg",
                480 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-480x480-70.jpg",
                960 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-960x960-70.jpg",
            ],
                topping: new ToppingDTO(
                    topping: [
                    [
                        "id" => "841ed46e-fae5-4df5-aabc-6a98155afe2f",
                        "name" => "Соус Альфредо",
                    ],
                    [
                        "id" => "0d0b0510-9520-44a8-9947-2efd2c0f0504",
                        "name" => "Моцарела",
                    ],
                    [
                        "id" => "aea741b2-1847-4519-9f26-d598b69f1bb9",
                        "name" => "Гриби",
                    ],
                    [
                        "id" => "311f1e0e-6ecc-4d36-8340-87bcdcb4c4e1",
                        "name" => "Пепероні",
                    ]]
                ),
                sizes: new SizeDTO(
                    size: [
                    [
                        "id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                        "name" => "Стандартна"],
                    ["id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                        "name" => "Велика"]
                ]
                ),
                flavors: new FlavorDTO(
                    flavor: [
                    [
                        "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                        "name" => "Стандартне",
                    ],
                    [
                        "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                        "name" => "Тонке",
                    ],
                    [
                        "id" => "924937c7-1637-4162-aac0-8284f71173a7",
                        "name" => "Борт Філадельфія",
                    ],
                    [
                        "id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                        "name" => "Борт Хот-Дог"
                    ],
                    [
                        "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                        "name" => "Стандартне"
                    ],
                    [
                        "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                        "name" => "Тонке"
                    ],
                    [
                        "id" => "924937c7-1637-4162-aac0-8284f71173a7",
                        "name" => "Борт Філадельфія"
                    ],
                    [
                        "id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                        "name" => "Борт Хот-Дог"
                    ]
                ]
                ),
                attribute: new ProductSizeDTO(
                    attribute: [
                        [
                            "size_id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "flavor_id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                            "price" => 255
                        ],
                        [
                            "size_id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "flavor_id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                            "price" => 180.0
                        ],
                        [
                            "size_id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "flavor_id" => "924937c7-1637-4162-aac0-8284f71173a7",
                            "price" => 215.0
                        ],
                        [
                            "size_id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "flavor_id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                            "price" => 230.0
                        ],
                        [
                            "size_id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "flavor_id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                            "price" => 212.0
                        ],
                        [
                            "size_id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "flavor_id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                            "price" => 212.0
                        ],
                        [
                            "size_id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "flavor_id" => "924937c7-1637-4162-aac0-8284f71173a7",
                            "price" => 257.0
                        ],
                        [
                            "size_id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "flavor_id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                            "price" => 272.0
                        ]
                    ]
                )
            )
        ];
    }
}
