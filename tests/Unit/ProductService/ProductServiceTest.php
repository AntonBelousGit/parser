<?php
declare(strict_types=1);

namespace Tests\Unit\ProductService;

use App\Models\History;
use App\Models\Product;
use App\Services\ParserService\Attribute;
use App\Services\ProductService\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateProduct()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->update($productData);
        $searchProduct = Product::find($productData[0]['id']);
        $this->assertEquals($productData[0]['name'], $searchProduct->name);
    }

    public function testNewProductCheckTopping()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->update($productData);
        $searchProduct = Product::with('topping')->find($productData[0]['id']);
        $this->assertEquals($productData[0]['name'], $searchProduct->name);
        $this->assertContains('Шинка', $searchProduct->topping->where('id', 'fa4b63ee-d0d0-4a6f-b2ff-4f39dbeb0342')->pluck('name'));
    }

    public function testNewProductCheckSize()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->update($productData);
        $searchProduct = Product::with('topping')->find($productData[0]['id']);
        $findAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(205.0, $findAttribute->price);
    }

    public function testUpdateSizeFlavorPrice()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->update($productData);
        $searchProduct = Product::with('topping')->find($productData[0]['id']);
        $findAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(205.0, $findAttribute->price);

        $updateProductData = $this->updateTestProductData();
        $service->update($updateProductData);
        $findNewAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(255.0, $findNewAttribute->price);
        $this->assertNotEquals(255.0, $findAttribute->price);
    }


    public function testCheckHistoryUpdateSizeFlavorPrice()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->update($productData);
        $searchProduct = Product::with('topping')->find($productData[0]['id']);
        $findAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(205.0, $findAttribute->price);

        $updateProductData = $this->updateTestProductData();
        $service->update($updateProductData);
        $findNewAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6', 'flavor_id' => '8cce4b72-3386-415c-b983-70711ea235e7'])->first();
        $this->assertEquals(255.0, $findNewAttribute->price);
        $this->assertNotEquals(255.0, $findAttribute->price);

        $history = History::where(['historical_type' => 'App\Models\Attribute', 'historical_id' => $findNewAttribute->id])->first();

        $this->assertEquals(205, $history->changed_value_from);
        $this->assertEquals(255, $history->changed_value_to);
    }

    /**
     * @return array
     */
    protected function getTestProductData(): array
    {
        return
            [
                [
                    "id" => "53aa39d4-500e-4b41-9d42-9e901707335f",
                    "name" => "Піца Шинка та гриби",
                    "image" => [
                        "full" => "https://media.dominos.ua/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1.jpg",
                        1150 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1-thumbnail-1150x1150-70.jpg",
                        480 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1-thumbnail-480x480-70.jpg",
                        2300 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1-thumbnail-2300x2300-70.jpg",
                        960 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1-thumbnail-960x960-70.jpg",
                    ],
                    "image_mobile" => [
                        "full" => "https://media.dominos.ua/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1.jpg",
                        1150 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1-thumbnail-1150x1150-70.jpg",
                        480 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1-thumbnail-480x480-70.jpg",
                        2300 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1-thumbnail-2300x2300-70.jpg",
                        960 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1-thumbnail-960x960-70.jpg",
                    ],
                    "toppings" => [
                        [
                            "id" => "57b9883e-7652-4590-9316-f45f2da2cad4",
                            "name" => "Соус Domino's",
                        ],
                        [
                            "id" => "fa4b63ee-d0d0-4a6f-b2ff-4f39dbeb0342",
                            "name" => "Шинка",
                        ],
                        [
                            "id" => "0d0b0510-9520-44a8-9947-2efd2c0f0504",
                            "name" => "Моцарела",
                        ],
                        [
                            "id" => "aea741b2-1847-4519-9f26-d598b69f1bb9",
                            "name" => "Гриби",
                        ]
                    ],
                    "sizes" => [
                        [
                            "id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "name" => "Стандартна",
                            "flavors" => [
                                [
                                    "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                                    "product" => [
                                        "price" => 205.0
                                    ]
                                ],
                                [
                                    "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                                    "product" => [
                                        "price" => 205.0
                                    ]
                                ],
                                [
                                    "id" => "924937c7-1637-4162-aac0-8284f71173a7",
                                    "product" => [
                                        "price" => 240.0
                                    ]
                                ],
                                [
                                    "id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                                    "product" => [
                                        "price" => 255.0
                                    ]
                                ],
                            ],
                        ],
                        [
                            "id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "name" => "Велика",
                            "flavors" => [
                                [
                                    "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                                    "product" => [
                                        "price" => 245.0
                                    ]
                                ],
                                [
                                    "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                                    "product" => [
                                        "price" => 245.0
                                    ]
                                ],
                                [
                                    "id" => "924937c7-1637-4162-aac0-8284f71173a7",
                                    "product" => [
                                        "price" => 290.0
                                    ]
                                ],
                                [
                                    "id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                                    "product" => [
                                        "price" => 305.0
                                    ]
                                ],
                            ],
                        ]
                    ]
                ],
            ];
    }

    /**
     * @return array
     */
    protected function updateTestProductData(): array
    {
        return
            [
                [
                    "id" => "53aa39d4-500e-4b41-9d42-9e901707335f",
                    "name" => "Піца Шинка та хуи",
                    "image" => [
                        "full" => "https://media.dominos.ua/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1.jpg",
                        1150 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1-thumbnail-1150x1150-70.jpg",
                        480 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1-thumbnail-480x480-70.jpg",
                        2300 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1-thumbnail-2300x2300-70.jpg",
                        960 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2020/12/29/Vetchina_i_griby_1-thumbnail-960x960-70.jpg",
                    ],
                    "image_mobile" => [
                        "full" => "https://media.dominos.ua/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1.jpg",
                        1150 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1-thumbnail-1150x1150-70.jpg",
                        480 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1-thumbnail-480x480-70.jpg",
                        2300 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1-thumbnail-2300x2300-70.jpg",
                        960 => "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/03/09/Vetchina_i_griby_1-thumbnail-960x960-70.jpg",
                    ],
                    "toppings" => [
                        [
                            "id" => "57b9883e-7652-4590-9316-f45f2da2cad4",
                            "name" => "Соус Domino's",
                        ],
                        [
                            "id" => "fa4b63ee-d0d0-4a6f-b2ff-4f39dbeb0342",
                            "name" => "Шинка",
                        ],
                        [
                            "id" => "0d0b0510-9520-44a8-9947-2efd2c0f0504",
                            "name" => "Моцарела",
                        ],
                        [
                            "id" => "aea741b2-1847-4519-9f26-d598b69f1bb9",
                            "name" => "Гриби",
                        ]
                    ],
                    "sizes" => [
                        [
                            "id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
                            "name" => "Стандартна",
                            "flavors" => [
                                [
                                    "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                                    "product" => [
                                        "price" => 255.0
                                    ]
                                ],
                                [
                                    "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                                    "product" => [
                                        "price" => 205.0
                                    ]
                                ],
                                [
                                    "id" => "924937c7-1637-4162-aac0-8284f71173a7",
                                    "product" => [
                                        "price" => 240.0
                                    ]
                                ],
                                [
                                    "id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                                    "product" => [
                                        "price" => 255.0
                                    ]
                                ],
                            ],
                        ],
                        [
                            "id" => "fb739582-296f-484f-b3a4-2da8f6c7f57c",
                            "name" => "Велика",
                            "flavors" => [
                                [
                                    "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
                                    "product" => [
                                        "price" => 245.0
                                    ]
                                ],
                                [
                                    "id" => "154f73b6-5b9d-4168-ab13-85e8003fda56",
                                    "product" => [
                                        "price" => 245.0
                                    ]
                                ],
                                [
                                    "id" => "924937c7-1637-4162-aac0-8284f71173a7",
                                    "product" => [
                                        "price" => 290.0
                                    ]
                                ],
                                [
                                    "id" => "6a78ca88-800b-4c32-9057-76e56252f8b4",
                                    "product" => [
                                        "price" => 305.0
                                    ]
                                ],
                            ],
                        ]
                    ]
                ],
            ];
    }
}
