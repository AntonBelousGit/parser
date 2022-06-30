<?php

declare(strict_types=1);

namespace App\Services\BaseServices\DataBaseService;

use App\Models\Product;
use App\Repositories\AttributeRepositories;
use App\Repositories\ProductRepositories;
use App\Services\BaseServices\DataBaseService\Contracts\DataBaseServiceContract;
use App\Services\BaseServices\Product as ParsedProduct;
use Illuminate\Support\Arr;
use Throwable;

class DataBaseService implements DataBaseServiceContract
{
    /**
     * @param ProductRepositories $productRepositories
     * @param AttributeRepositories $attributeRepositories
     */
    public function __construct(
        protected ProductRepositories $productRepositories,
        protected AttributeRepositories $attributeRepositories,
    ) {
    }

    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void
    {
        try {
            foreach ($array as $item) {
                try {
                    $updateProduct = $this->productRepositories->getProductByID($item->id);
                    if ($updateProduct) {
                        $this->updateProduct($updateProduct, $item);
                    } else {
                        $this->createProduct($item);
                    }
                } catch (Throwable $exception) {
                    report('DataBaseService error create/update' . $exception);
                }
            }
        } catch (Throwable) {
            report('DataBaseService update error');
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
            } catch (Throwable $exception) {
                report('ProductService error in createProduct - topping attach' . $exception);
            }
            try {
                $product->attributeProduct()->createMany($item->attribute->attribute);
            } catch (Throwable $exception) {
                report($exception);
            }
        } catch (Throwable $exception) {
            report('ProductService error in createProduct' . $exception);
        }
    }

    /**
     * @param Product $product
     * @param ParsedProduct $data
     * @return void
     */

    protected function updateProduct(Product $product, ParsedProduct $data): void
    {
        $product->update(['id' => $data->id, 'name' => $data->name, 'image' => $data->image, 'image_mobile' => $data->imageMobile]);
        try {
            $product->topping()->attach(Arr::pluck($data->topping->topping, 'id'));

            foreach ($data->attribute->attribute as $item) {
                $data = [
                    'product_id' => $product->id,
                    'size_id' => $item['size_id'],
                    'flavor_id' => $item['flavor_id']
                ];
                $attribute = $this->attributeRepositories->getAttributeFromArray($data);

                if ($attribute) {
                    $attribute->update(['price' => $item['price']]);
                } else {
                    $product->attributeProduct()->create($item);
                }
            }
        } catch (Throwable) {
            report('ProductService error in updateProduct');
        }
    }
}
