<?php

declare(strict_types=1);

namespace App\Services\ProductService;


use App\Models\Product;
use App\Repositories\ProductRepositories;
use App\Services\ProductService\Contracts\ProductServiceContract;
use App\Services\ProductService\Contracts\ProductValidatorContract;
use Illuminate\Support\Arr;
use Throwable;

class ProductService implements ProductServiceContract
{

    protected array $sizes = [];

    /**
     * @param ProductValidatorContract $validatorContract
     * @param ProductRepositories $productRepositories
     */
    public function __construct(
        protected ProductValidatorContract $validatorContract,
        protected ProductRepositories $productRepositories,
    )
    {
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function store(array $array = []): bool
    {
        try {

            foreach ($array as $item) {


                $item = $this->validatorContract->validate($item);


                $item['name'] = html_entity_decode($item['name']);

                try {
                    $this->createProduct($item);
                } catch (Throwable $e) {

                    report($e);
                    continue;
                }
            }
        } catch (Throwable $e) {
            report($e);
            return false;
        }
        return true;
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
                    $update_product = $this->productRepositories->getProductByID($item['id']);
//                    dd($update_product);
                    if ($update_product) {
                        $this->updateProduct($update_product, $item);
                    } else {
                        $this->createProduct($item);
                    }

                } catch (Throwable $e) {

                    report($e);
                    continue;
                }
            }
        } catch (Throwable $e) {
            report($e);
            return false;
        }
        return true;
    }

    /**
     * @param array $item
     */

    protected function createProduct(array $item)
    {
        try {
            $product = (new Product())->create($item);
            $product->topping()->attach(Arr::pluck($item['toppings'], 'id'));

            foreach ($item['sizes'] as $size) {
                foreach ($size['flavors'] as $flavor) {
                    $product->size()->attach($size['id'], ['flavor_id' => $flavor['id'], 'price' => $flavor['product']['price']]);
                }
            }
        } catch (Throwable $e) {

            report($e);
        }
    }

    protected function updateProduct(Product $product, array $data)
    {
        $product->update($data);

        try {
            $product->topping()->sync(Arr::pluck($data['toppings'], 'id'));

            foreach ($data['sizes'] as $size) {
                foreach ($size['flavors'] as $flavor) {

                    dump(Arr::pluck($size['flavors'],'id'));
//                    dd($size['id'],['flavor_id' => $flavor['id'], 'price' => $flavor['product']['price']]);
//                    $product->size()->updateExistingPivot($size['id'], ['flavor_id' => $flavor['id'], 'price' => $flavor['product']['price']]);
//                    $product->size()->updateExistingPivot($size['id'], ['flavor_id' => $flavor['id'], 'price' => $flavor['product']['price']]);
                    $sdf = $product->sraka()->save( ['size_id'=>$size['id'],'flavor_id' => $flavor['id'], 'price' => $flavor['product']['price']]);
//                    dd($sdf);
                }
            }

        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }
}
