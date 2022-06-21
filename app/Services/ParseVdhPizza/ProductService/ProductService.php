<?php

declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ProductService;

use App\Models\Product;
use App\Repositories\AttributeRepositories;
use App\Repositories\ProductRepositories;
use App\Services\ParseVdhPizza\ProductService\Contracts\ProductServiceContract;
use App\Services\ParseVdhPizza\ProductService\Contracts\ProductValidatorContract;
use Illuminate\Support\Arr;
use Throwable;

class ProductService implements ProductServiceContract
{
    /**
     * @param ProductValidatorContract $validatorContract
     * @param ProductRepositories $productRepositories
     * @param AttributeRepositories $attributeRepositories
     */
    public function __construct(
        protected ProductValidatorContract $validatorContract,
        protected ProductRepositories $productRepositories,
        protected AttributeRepositories $attributeRepositories,
    ) {
    }

    /**
     * @param array $array
     * @return bool
     */
    public function updateOrCreate(array $array = []): bool
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
                } catch (Throwable) {
                    report('ParseVdhPizza ProductService error create/update');
                    continue;
                }
            }
        } catch (Throwable) {
            report('ParseVdhPizza ProductService update error');
            return false;
        }
        return true;
    }

    /**
     * @param \App\Services\ParseVdhPizza\ParserService\Product $item
     * @return bool
     */
    protected function createProduct(\App\Services\ParseVdhPizza\ParserService\Product $item): bool
    {
        try {
            $product = Product::create(['id' => $item->id, 'name' => $item->name, 'image' => $item->image, 'image_mobile' => $item->image]);
            $product->topping()->attach(Arr::pluck($item->topping->topping, 'id'));
            $product->sizes()->attach(Arr::pluck($item->attribute->attribute, 'id'), ['flavor_id' => '', 'price' => $item->attribute->price]);
            return true;
        } catch (Throwable) {
            report('ParseVdhPizza ProductService error in createProduct');
            return false;
        }
    }

    /**
     * @param Product $product
     * @param \App\Services\ParseVdhPizza\ParserService\Product $item
     * @return bool
     */

    protected function updateProduct(Product $product, \App\Services\ParseVdhPizza\ParserService\Product $item): bool
    {
        $product->update(['id' => $item->id, 'name' => $item->name, 'image' => $item->image, 'image_mobile' => $item->image]);
        try {
            $product->topping()->sync(Arr::pluck($item->topping->topping, 'id'));
            foreach ($item->attribute->attribute as $attr) {
                $data = [
                    'product_id' => $product->id,
                    'size_id' => $attr['id'],
                    'flavor_id' => ''
                ];

                $attribute = $this->attributeRepositories->getAttributeFromArray($data);

                if ($attribute) {
                    $attribute->update(['price' => $item->attribute->price]);
                } else {
                    $product->sizes()->attach(Arr::pluck($item->attribute->attribute, 'id'), ['flavor_id' => '', 'price' => $item->attribute->price]);
                }
            }
            return true;
        } catch (Throwable) {
            report('ParseVdhPizza ProductService error in updateProduct');
            return false;
        }
    }
}
