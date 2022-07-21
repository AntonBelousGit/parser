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
        'size' => Size::class,
        'flavor' => Flavor::class,
        'topping' => Topping::class
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
     * @param $data
     * @return void
     * @throws Exception\InvalidStoreServiceDataException
     * @throws Throwable
     */
    public function store($data): void
    {
        $this->updateOrCreateAttribute($data->attributes);
        $this->updateOrCreateProduct($data->products);
    }

    /**
     * Update or Create Product method
     *
     * @param array $array
     * @return void
     */
    protected function updateOrCreateProduct(array $array): void
    {
        foreach ($array as $item) {
            $updateProduct = $this->productRepositories->getProductByID($item->id);
            if ($updateProduct) {
                $this->updateProduct($updateProduct, $item);
            } else {
                $this->createProduct($item);
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
        $product = Product::create(['id' => $item->id, 'name' => $item->name, 'image' => $item->image, 'image_mobile' => $item->imageMobile]);
        $product->topping()->attach(Arr::pluck($item->topping, 'id'));
        $product->attributeProduct()->createMany($item->attribute->attribute);
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
        $product->update(['id' => $data->id, 'name' => $data->name, 'image' => $data->image, 'image_mobile' => $data->imageMobile]);
        $product->topping()->sync(Arr::pluck($data->topping, 'id'));
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
        foreach ($attributeData->flatten() as $item) {
            $item = $this->attributeValidator->validate(['id' => $item->id, 'name' => $item->name]);
            (self::ATTRIBUTEMODEL[$attributeKey])::query()->updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
