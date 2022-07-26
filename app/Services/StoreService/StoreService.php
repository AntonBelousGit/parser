<?php

declare(strict_types=1);

namespace App\Services\StoreService;

use App\Models\Attribute;
use App\Models\Flavor;
use App\Models\Product;
use App\Models\Size;
use App\Models\Topping;
use App\Repositories\ProductRepositories;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO as ParsedProduct;
use App\Services\StoreService\Contracts\StoreServiceContract;
use App\Services\StoreService\Validator\AttributeValidator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Throwable;

class StoreService implements StoreServiceContract
{
    /**
     * Attribute model
     */
    const ATTRIBUTEMODEL = [
        'sizes' => Size::class,
        'flavors' => Flavor::class,
        'toppings' => Topping::class
    ];

    /**
     * StoreService constructor.
     *
     * @param ProductRepositories $productRepositories
     * @param AttributeValidator $attributeValidator
     */
    public function __construct(
        protected ProductRepositories $productRepositories,
        protected AttributeValidator $attributeValidator,
    ) {
    }

    /**
     * Store or update parsed data
     *
     * @param ParserProductDataDTO $data
     * @return void
     * @throws Exception\InvalidStoreServiceDataException
     * @throws Throwable
     */
    public function store(ParserProductDataDTO $data): void
    {
        $this->updateOrCreateAttribute($data->attributes);
        $this->updateOrCreateProduct($data->products);
    }

    /**
     * Update or Create Product method
     *
     * @param Collection $products
     * @return void
     */
    protected function updateOrCreateProduct(Collection $products): void
    {
        foreach ($products as $product) {
            $updateProduct = $this->productRepositories->getProductByID($product->id);
            if ($updateProduct) {
                $this->updateProduct($updateProduct, $product);
            } else {
                $this->createProduct($product);
            }
        }
    }

    /**
     * Create Product
     *
     * @param ParsedProduct $item
     * @return void
     */
    protected function createProduct(ParsedProduct $item): void
    {
        $product = Product::create(['id' => $item->id, 'name' => $item->name, 'image' => $item->images, 'image_mobile' => $item->imagesMobile]);
        $product->topping()->attach(Arr::pluck($item->toppings, 'id'));
        if (!empty($item->attributes->attributes)) {
            $product->attributeProduct()->createMany($item->attributes->attributes);
        }
    }

    /**
     * Update Product
     *
     * @param Product $product
     * @param ParsedProduct $data
     * @return void
     */
    protected function updateProduct(Product $product, ParsedProduct $data): void
    {
        $product->update(['id' => $data->id, 'name' => $data->name, 'image' => $data->images, 'image_mobile' => $data->imagesMobile]);
        $product->topping()->sync(Arr::pluck($data->toppings, 'id'));
        if (!empty($data->attributes->attributes)) {
            foreach ($data->attributes->attributes as $attribute) {
                $data = [
                    'product_id' => $product->id,
                    'size_id' => $attribute['size_id'],
                    'flavor_id' => $attribute['flavor_id']
                ];
                $attributeModel = Attribute::where($data)->first();
                if ($attributeModel) {
                    $attributeModel->update(['price' => $attribute['price']]);
                } else {
                    $product->attributeProduct()->create($attribute);
                }
            }
        }
    }

    /**
     * Update or Create product attribute (size, flavor, topping, etc.)
     *
     * @param AttributeDTO $attribute
     * @return void
     * @throws Exception\InvalidStoreServiceDataException
     * @throws Throwable
     */
    protected function updateOrCreateAttribute(AttributeDTO $attribute): void
    {
        foreach ($attribute as $attributeKey => $attributeData) {
            $this->attribute($attributeKey, $attributeData);
        }
    }

    /**
     * Save or Update attribute to DB
     *
     * @param string $attributeKey
     * @param Collection $attributeData
     * @throws Exception\InvalidStoreServiceDataException|Throwable
     */
    protected function attribute(string $attributeKey, Collection $attributeData): void
    {
        foreach ($attributeData->flatten() as $attribute) {
            $attribute = $this->attributeValidator->validate(['id' => $attribute->id, 'name' => $attribute->name]);
            (self::ATTRIBUTEMODEL[$attributeKey])::query()->updateOrCreate(['id' => $attribute['id']], $attribute);
        }
    }
}
