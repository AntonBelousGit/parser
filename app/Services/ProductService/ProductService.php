<?php

declare(strict_types=1);

namespace App\Services\ProductService;

use App\Models\Product;
use App\Repositories\AttributeRepositories;
use App\Repositories\ProductRepositories;
use App\Services\ProductService\Contracts\ProductServiceContract;
use App\Services\ProductService\Contracts\ProductValidatorContract;
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
    public function update(array $array = []): bool
    {
        try {
            foreach ($array as $item) {
                $item = $this->validatorContract->validate($item);
                $item['name'] = html_entity_decode($item['name']);

                try {
                    $updateProduct = $this->productRepositories->getProductByID($item['id']);
                    if ($updateProduct) {
                        $this->updateProduct($updateProduct, $item);
                    } else {
                        $this->createProduct($item);
                    }
                } catch (Throwable $exception) {
                    report('ProductService error create/update' . $exception);
                    continue;
                }
            }
        } catch (Throwable) {
            report('ProductService update error');
            return false;
        }
        return true;
    }

    /**
     * @param array $item
     * @return bool
     */
    protected function createProduct(array $item): bool
    {
        try {
            $product = Product::create($item);
            $product->topping()->attach(Arr::pluck($item['toppings'], 'id'));

            foreach ($item['sizes'] as $size) {
                foreach ($size['flavors'] as $flavor) {
                    $this->attachAttribute($product, $size['id'], $flavor);
                }
            }
            return true;
        } catch (Throwable) {
            report('ProductService error in createProduct');
            return false;
        }
    }

    /**
     * @param Product $product
     * @param array $data
     * @return bool
     */

    protected function updateProduct(Product $product, array $data): bool
    {
        $product->update($data);
        try {
            $product->topping()->sync(Arr::pluck($data['toppings'], 'id'));

            foreach ($data['sizes'] as $size) {
                foreach ($size['flavors'] as $flavor) {
                    $data = [
                        'product_id' => $product->id,
                        'size_id' => $size['id'],
                        'flavor_id' => $flavor['id']
                    ];

                    $attribute = $this->attributeRepositories->getAttributeFromArray($data);

                    if ($attribute) {
                        $attribute->update($data + ['price' => $flavor['product']['price']]);
                    } else {
                        $this->attachAttribute($product, $size['id'], $flavor);
                    }
                }
            }
            return true;
        } catch (Throwable) {
            report('ProductService error in updateProduct');
            return false;
        }
    }

    /**
     * @param $product
     * @param string $size
     * @param array $flavor
     */

    protected function attachAttribute($product, string $size, array $flavor)
    {
        try {
            $product->sizes()->attach($size, ['flavor_id' => $flavor['id'], 'price' => $flavor['product']['price']]);
        } catch (Throwable) {
            report('Attach failed');
        }
    }
}
