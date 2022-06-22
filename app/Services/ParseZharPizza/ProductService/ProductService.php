<?php

declare(strict_types=1);

namespace App\Services\ParseZharPizza\ProductService;

use App\Models\Product;
use App\Repositories\AttributeRepositories;
use App\Repositories\ProductRepositories;
use App\Services\ParseZharPizza\ProductService\Contracts\ProductServiceContract;
use App\Services\ParseZharPizza\ProductService\Contracts\ProductValidatorContract;
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
                } catch (Throwable) {
                    report('ProductService error create/update');
                }
            }
        } catch (Throwable) {
            report('ProductService update error');
        }
    }

    /**
     * @param \App\Services\ParseZharPizza\ParserService\Product $item
     * @return void
     */
    protected function createProduct(\App\Services\ParseZharPizza\ParserService\Product $item): void
    {
        try {
            $product = Product::create(['id' => $item->id, 'name' => $item->name, 'image' => $item->image, 'image_mobile' => $item->image]);
            $product->topping()->attach(Arr::pluck($item->topping->topping, 'id'));
            $product->sizes()->attach(Arr::pluck($item->attribute->attribute, 'id'), ['flavor_id' => '', 'price' => $item->attribute->price]);
        } catch (Throwable) {
            report('ProductService error in createProduct');
        }
    }

    /**
     * @param Product $product
     * @param \App\Services\ParseZharPizza\ParserService\Product $item
     * @return void
     */

    protected function updateProduct(Product $product, \App\Services\ParseZharPizza\ParserService\Product $item): void
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
        } catch (Throwable) {
            report('ProductService error in updateProduct');
        }
    }
}
