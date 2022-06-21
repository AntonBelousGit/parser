<?php
declare(strict_types=1);

namespace Tests\Unit\ParseZharPizza\ProductService;

use App\Models\History;
use App\Models\Product;
use App\Services\ParseZharPizza\ParserService\Attribute;
use App\Services\ParseZharPizza\ParserService\ProductSize;
use App\Services\ParseZharPizza\ParserService\Topping;
use App\Services\ParseZharPizza\ParserService\ZharPizzaParseService;
use App\Services\ParseZharPizza\ProductService\ProductService;
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
        $this->assertContains('Красный соус', $searchProduct->topping->where('id', 'krasnyi-sous')->pluck('name'));
    }

    public function testNewProductCheckSize()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->updateOrCreate([$productData]);
        $searchProduct = Product::with('topping')->find($productData->id);
        $findAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => '35-sm', 'flavor_id' => ''])->first();
        $this->assertEquals(210, $findAttribute->price);
    }

    public function testUpdateSizeFlavorPrice()
    {
        $this->seed();
        $productData = $this->getTestProductData();
        $service = $this->app->make(ProductService::class);
        $service->updateOrCreate([$productData]);
        $searchProduct = Product::with('topping')->find($productData->id);
        $findAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => '35-sm', 'flavor_id' => ''])->first();
        $this->assertEquals(210, $findAttribute->price);

        $updateProductData = $this->updateTestProductData();
        $service->updateOrCreate([$updateProductData]);
        $findNewAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => '35-sm', 'flavor_id' => ''])->first();
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
        $findAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => '35-sm', 'flavor_id' => ''])->first();
        $this->assertEquals(210, $findAttribute->price);

        $updateProductData = $this->updateTestProductData();
        $service->updateOrCreate([$updateProductData]);
        $findNewAttribute = \App\Models\Attribute::where(['product_id' => $searchProduct->id, 'size_id' => '35-sm', 'flavor_id' => ''])->first();
        $this->assertEquals(215.0, $findNewAttribute->price);
        $this->assertNotEquals(215.0, $findAttribute->price);

        $history = History::where(['historical_type' => 'App\Models\Attribute', 'historical_id' => $findNewAttribute->id])->orderBy('id', 'desc')->first();
        $this->assertEquals(210, $history->changed_value_from);
        $this->assertEquals(215, $history->changed_value_to);
    }


    /**
     * @return ZharPizzaParseService
     */
    protected function getDominiParse(): ZharPizzaParseService
    {
        return $this->app->make(ZharPizzaParseService::class);
    }

    /**
     * @return \App\Services\ParseZharPizza\ParserService\Product
     */
    protected function getTestProductData(): \App\Services\ParseZharPizza\ParserService\Product
    {
        return
            new \App\Services\ParseZharPizza\ParserService\Product(
                id: '781674722771',
                name: 'Жар-пицца',
                image: 'https://static.tildacdn.com/tild3565-3331-4164-b836-346538306539/DSCF1849_-_.jpg',
                topping: new Topping(
                    topping: [
                    [
                        "id" => "krasnyi-sous",
                        "name" => "Красный соус",
                    ],
                    [
                        "id" => "saslyk-kurinyi",
                        "name" => "шашлык куриный"
                    ]
                ]
                ),
                attribute: new ProductSize(
                    attribute: [
                        [
                            "id" => "35-sm",
                            "name" => "35 см"
                        ]
                    ],
                    price: 210.0
                )
            );
    }

    /**
     * @return \App\Services\ParseZharPizza\ParserService\Product
     */
    protected function updateTestProductData(): \App\Services\ParseZharPizza\ParserService\Product
    {
        return
            new \App\Services\ParseZharPizza\ParserService\Product(
                id: '781674722771',
                name: 'Жар-пицца',
                image: 'https://static.tildacdn.com/tild3565-3331-4164-b836-346538306539/DSCF1849_-_.jpg',
                topping: new Topping(
                    topping: [
                    [
                        "id" => "krasnyi-sous",
                        "name" => "Красный соус",
                    ],
                    [
                        "id" => "saslyk-kurinyi",
                        "name" => "шашлык куриный"
                    ]
                ]
                ),
                attribute: new ProductSize(
                    attribute: [
                    [
                        "id" => "35-sm",
                        "name" => "35 см"
                    ]
                ],
                    price: 215.0
                )
            );
    }
}
