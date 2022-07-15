<?php
declare(strict_types=1);

namespace StoreManagerTests;

use App\Models\Attribute;
use App\Models\Flavor;
use App\Models\History;
use App\Models\Product;
use App\Models\Size;
use App\Models\Topping;
use App\Services\StoreService\StoreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class StoreManagerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected array $users = [];
    protected string $id;
    protected Flavor $flavor;
    protected Size $size;
    protected Topping $topping;

    public function setUp(): void
    {
        parent::setUp();
        $this->topping = Topping::factory()->create();
        $this->flavor = Flavor::factory()->create();
        $this->size = Size::factory()->create();
        $this->id = uniqid();
        Product::factory()->create(['id' => $this->id])->each(function ($product) {
            $product->topping()->attach($this->topping->id);
            $product->attributeProduct()
                ->create([ 'product_id' => $product->id, 'size_id' => $this->size['id'], 'flavor_id' => $this->flavor['id'], 'price' => 180]);
        });
    }

    public function testCreateProduct()
    {
        $productData = $this->getTestProductData(uniqid(), $this->size, $this->flavor, $this->topping, 250);
        $service = $this->app->make(StoreService::class);
        $service->store($productData);
        $searchProduct = Product::find($productData[0]->products[0]->id);
        $this->assertEquals($productData[0]->products[0]->name, $searchProduct->name);
    }

    public function testUpdateProduct()
    {
        $beforeUpdateProduct = Product::with('topping', 'attributeProduct')->find($this->id);
        $productData = $this->getTestProductData($this->id, $this->size, $this->flavor, $this->topping, 250);
        $service = $this->app->make(StoreService::class);
        $service->store($productData);
        $afterUpdateProduct = Product::with('topping', 'attributeProduct')->find($this->id);
        $this->checkTwoModelAssertNotEquals($beforeUpdateProduct, $afterUpdateProduct);
        $this->checkEqualsUpdatedDataUpdatedProduct($productData, $afterUpdateProduct);
    }

    public function testUpdateSizeFlavorPrice()
    {
        $beforeUpdateProduct = Product::find($this->id);
        $findAttribute = Attribute::where(['product_id' => $beforeUpdateProduct->id, 'size_id' => $this->size['id'], 'flavor_id' => $this->flavor['id']])->first();
        $this->assertEquals(180, $findAttribute->price);
        $productData = $this->getTestProductData($this->id, $this->size, $this->flavor, $this->topping, 250);
        $service = $this->app->make(StoreService::class);
        $service->store($productData);
        $findNewAttribute = Attribute::where(['product_id' => $beforeUpdateProduct->id, 'size_id' => $this->size['id'], 'flavor_id' => $this->flavor['id']])->first();
        $this->assertEquals(250, $findNewAttribute->price);
    }

    public function testCheckHistoryUpdateSizeFlavorPrice()
    {
        $beforeUpdateProduct = Product::find($this->id);
        $findAttribute = Attribute::where(['product_id' => $beforeUpdateProduct->id, 'size_id' => $this->size['id'], 'flavor_id' => $this->flavor['id']])->first();
        $this->assertEquals(180, $findAttribute->price);
        $productData = $this->getTestProductData($this->id, $this->size, $this->flavor, $this->topping, 250);
        $service = $this->app->make(StoreService::class);
        $service->store($productData);
        $findNewAttribute = Attribute::where(['product_id' => $beforeUpdateProduct->id, 'size_id' => $this->size['id'], 'flavor_id' => $this->flavor['id']])->first();
        $this->assertEquals(250, $findNewAttribute->price);
        $history = History::where(['historical_type' => 'App\Models\Attribute', 'historical_id' => $findNewAttribute->id])->orderBy('id', 'desc')->first();
        $this->assertEquals(180, $history->changed_value_from);
        $this->assertEquals(250, $history->changed_value_to);
    }

    public function testUpdateAttribute()
    {
        $searchFlavor = Flavor::find($this->flavor->id);
        $searchSize = Size::find($this->size->id);
        $searchTopping = Topping::find($this->topping->id);
        $productData = $this->getTestProductData($this->id, $this->size, $this->flavor, $this->topping, 250);
        $service = $this->app->make(StoreService::class);
        $service->store($productData);
        $afterUpdateFlavor = Flavor::find($this->flavor->id);
        $afterUpdateSize = Size::find($this->size->id);
        $afterUpdateTopping = Topping::find($this->topping->id);
        $this->assertNotEquals($searchFlavor->name, $afterUpdateFlavor->name);
        $this->assertNotEquals($searchSize->name, $afterUpdateSize->name);
        $this->assertNotEquals($searchTopping->name, $afterUpdateTopping->name);
    }


    /**
     * Check equals update data dnd updated Product
     *
     * @param $productData
     * @param Product $testedModel
     * @param string[] $ignore
     */
    private function checkEqualsUpdatedDataUpdatedProduct($productData, Product $testedModel, array $ignore = ['created_at','updated_at'])
    {
        $productData = $productData[0]->products[0];
        $this->assertEquals($testedModel['id'], $productData->id);
        $this->assertEquals($testedModel['name'], $productData->name);
        $this->assertEquals($testedModel['image'], $productData->image);
        $this->assertEquals($testedModel['image'], $productData->image);
        $this->assertEquals($testedModel['image_mobile'], $productData->imageMobile);
        $this->assertEquals($testedModel['topping'][0]['id'], $productData->topping[0]->id);
        $this->assertEquals($testedModel['topping'][0]['name'], $productData->topping[0]->name);
        $this->assertEquals($testedModel['attributeProduct'][0]['size_id'], $productData->attribute->attribute[0]['size_id']);
        $this->assertEquals($testedModel['attributeProduct'][0]['flavor_id'], $productData->attribute->attribute[0]['flavor_id']);
        $this->assertEquals($testedModel['attributeProduct'][0]['price'], $productData->attribute->attribute[0]['price']);
    }

    /**
     * Check equals Model before update dnd after update
     *
     * @param Product $model
     * @param Product $checkProduct
     * @param string[] $ignore
     */
    private function checkTwoModelAssertNotEquals(Product $model, Product $checkProduct, array $ignore = ['created_at','updated_at'])
    {
        $model = Arr::except($model->toArray(), $ignore);
        foreach ($model as $key => $item) {
            if ($key === 'id') {
                $this->assertEquals($model['id'], $checkProduct->id);
                continue;
            }
            $this->assertNotEquals($item, $checkProduct->$key);
        }
    }
}
