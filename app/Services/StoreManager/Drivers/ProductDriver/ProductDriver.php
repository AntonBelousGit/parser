<?php
declare(strict_types=1);

namespace App\Services\StoreManager\Drivers\ProductDriver;

use App\Models\Attribute;
use App\Models\Product;
use App\Repositories\ProductRepositories;
use App\Services\ParserManager\DTOs\ProductDTO as ParsedProduct;
use App\Services\StoreManager\Contracts\ProductDriverContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductDriver implements ProductDriverContract
{
    /**
     * @param ProductRepositories $productRepositories
     */
    public function __construct(
        protected ProductRepositories $productRepositories,
    ) {
    }

    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void
    {
        foreach ($array as $item) {
            try {
                $updateProduct = $this->productRepositories->getProductByID($item->id);
                if ($updateProduct) {
                    $this->updateProduct($updateProduct, $item);
                } else {
                    $this->createProduct($item);
                }
            } catch (Throwable) {
                Log::info('DataBaseService error create/update');
            }
        }
    }

    /**
     * @param ParsedProduct $item
     * @return void
     */
    protected function createProduct(ParsedProduct $item): void
    {
        try {
            $product = Product::create(['id' => $item->id, 'name' => $item->name, 'image' => $item->image, 'image_mobile' => $item->imageMobile]);
            try {
                $product->topping()->attach(Arr::pluck($item->topping->topping, 'id'));
            } catch (Throwable) {
                Log::info('ProductService error in createProduct - topping attach', ['item' => $item]);
            }
            try {
                $product->attributeProduct()->createMany($item->attribute->attribute);
            } catch (Throwable) {
                Log::info('ProductService error in createProduct - attributeProduct', ['item' => $item]);
            }
        } catch (Throwable) {
            Log::info('ProductService error in createProduct', ['item' => $item]);
        }
    }

    /**
     * @param Product $product
     * @param ParsedProduct $data
     * @return void
     */
    protected function updateProduct(Product $product, ParsedProduct $data): void
    {
        try {
            $product->update(['id' => $data->id, 'name' => $data->name, 'image' => $data->image, 'image_mobile' => $data->imageMobile]);
            $product->topping()->attach(Arr::pluck($data->topping->topping, 'id'));
            foreach ($data->attribute->attribute as $item) {
                $data = [
                    'product_id' => $product->id,
                    'size_id' => $item['size_id'],
                    'flavor_id' => $item['flavor_id']
                ];
                $attribute = Attribute::where($data)->first();
                if ($attribute) {
                    $attribute->update(['price' => $item['price']]);
                } else {
                    $product->attributeProduct()->create($item);
                }
            }
        } catch (Throwable) {
            Log::info('ProductService error in updateProduct', ['data' => $data]);
        }
    }
}
