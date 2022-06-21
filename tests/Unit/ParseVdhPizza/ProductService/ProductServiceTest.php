<?php
declare(strict_types=1);

namespace Tests\Unit\ParseVdhPizza\ProductService;

use App\Models\Attribute;
use App\Models\History;
use App\Models\Product;
use App\Services\ParseVdhPizza\ParserService\ProductSize;
use App\Services\ParseVdhPizza\ParserService\Topping;
use App\Services\ParseVdhPizza\ParserService\VdhPizzaParseService;
use App\Services\ParseVdhPizza\ProductService\ProductService;
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
        $service->updateOrCreate([$productData]);
        $searchProduct = Product::find($productData->id);
        $this->assertEquals($productData->name, $searchProduct->name);
    }

    public function testNewProductCheckTopping()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->updateOrCreate([$productData]);
        $searchProduct = Product::with('topping')->find($productData->id);
        $this->assertEquals($productData->name, $searchProduct->name);
        $this->assertContains('Груша', $searchProduct->topping->where('id', 'grusa')->pluck('name'));
    }

    public function testNewProductCheckSize()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->updateOrCreate([$productData]);
        $searchProduct = Product::with('topping')->find($productData->id);
        $findAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'standard', 'flavor_id' => ''])->first();
        $this->assertEquals(210, $findAttribute->price);
    }

    public function testUpdateSizeFlavorPrice()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->updateOrCreate([$productData]);
        $searchProduct = Product::with('topping')->find($productData->id);
        $findAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'standard', 'flavor_id' => ''])->first();
        $this->assertEquals(210, $findAttribute->price);

        $updateProductData = $this->updateTestProductData();
        $service->updateOrCreate([$updateProductData]);
        $findNewAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'standard', 'flavor_id' => ''])->first();
        $this->assertEquals(215.0, $findNewAttribute->price);
        $this->assertNotEquals(215.0, $findAttribute->price);
    }


    public function testCheckHistoryUpdateSizeFlavorPrice()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->updateOrCreate([$productData]);
        $searchProduct = Product::with('topping')->find($productData->id);
        $findAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'standard', 'flavor_id' => ''])->first();
        $this->assertEquals(210, $findAttribute->price);

        $updateProductData = $this->updateTestProductData();
        $service->updateOrCreate([$updateProductData]);
        $findNewAttribute = Attribute::where(['product_id' => $searchProduct->id, 'size_id' => 'standard', 'flavor_id' => ''])->first();
        $this->assertEquals(215.0, $findNewAttribute->price);
        $this->assertNotEquals(215.0, $findAttribute->price);

        $history = History::where(['historical_type' => 'App\Models\Attribute', 'historical_id' => $findNewAttribute->id])->orderBy('id', 'desc')->first();

        $this->assertEquals(210, $history->changed_value_from);
        $this->assertEquals(215, $history->changed_value_to);
    }


    /**
     * @return VdhPizzaParseService
     */
    protected function getDominiParse(): VdhPizzaParseService
    {
        return $this->app->make(VdhPizzaParseService::class);
    }

    /**
     * @return \App\Services\ParseVdhPizza\ParserService\Product
     */
    protected function getTestProductData(): \App\Services\ParseVdhPizza\ParserService\Product
    {
        return
            new \App\Services\ParseVdhPizza\ParserService\Product(
                id: '205973539641',
                name: 'Дор Блю Груша',
                image: 'https://static.tildacdn.com/tild6537-3731-4732-b830-313161363437/__.jpg',
                topping: new Topping(
                    topping: [
                    [
                        "id" => "grusa",
                        "name" => "Груша"
                    ],
                    [
                        "id" => "dor-bliu",
                        "name" => "дор блю"
                    ],
                    [
                        "id" => "parmezan",
                        "name" => "пармезан"
                    ],
                    [
                        "id" => "mindalnyi-orex",
                        "name" => "миндальный орех"
                    ],
                    [
                        "id" => "mocarela",
                        "name" => "моцарела"
                    ],
                    [
                        "id" => "sous-belyi",
                        "name" => "соус белый"
                    ]
                ]
                ),
                attribute: new ProductSize(
                    attribute: [
                    [
                        "id" => "standard",
                        "name" => "Standard"
                    ]
                ],
                    price: 210.0
                )
            );
    }

    /**
     * @return \App\Services\ParseVdhPizza\ParserService\Product
     */
    protected function updateTestProductData(): \App\Services\ParseVdhPizza\ParserService\Product
    {
        return
            new \App\Services\ParseVdhPizza\ParserService\Product(
                id: '205973539641',
                name: 'Дор Блю Груша',
                image: 'https://static.tildacdn.com/tild6537-3731-4732-b830-313161363437/__.jpg',
                topping: new Topping(
                    topping: [
                    [
                        "id" => "grusa",
                        "name" => "Груша"
                    ],
                    [
                        "id" => "dor-bliu",
                        "name" => "дор блю"
                    ],
                    [
                        "id" => "parmezan",
                        "name" => "пармезан"
                    ],
                    [
                        "id" => "mindalnyi-orex",
                        "name" => "миндальный орех"
                    ],
                    [
                        "id" => "mocarela",
                        "name" => "моцарела"
                    ],
                    [
                        "id" => "sous-belyi",
                        "name" => "соус белый"
                    ]
                ]
                ),
                attribute: new ProductSize(
                    attribute: [
                    [
                        "id" => "standard",
                        "name" => "Standard"
                    ]
                ],
                    price: 215.0
                )
            );
    }
}
