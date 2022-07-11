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
use Illuminate\Support\Facades\Log;
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
     */
    public function store($data): void
    {
        try {
            foreach ($data as $item) {
                $this->updateOrCreateAttribute($item->attributes);
                $this->updateOrCreateProduct($item->products);
            }
        } catch (Throwable $exception) {
            Log::info('Store or update parsed data - problem', ['data' => $exception]);
        }
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
     * Create Product
     *
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
     * Update Product
     *
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

    /**
     * Update or Create product attribute (size, flavor, topping, etc.)
     *
     * @param AttributeDTO $attribute
     * @return void
     */
    protected function updateOrCreateAttribute(AttributeDTO $attribute): void
    {
        try {
            foreach ($attribute as $attributeKey => $attributeData) {
                $this->attribute($attributeKey, $attributeData);
            }
        } catch (Throwable $exception) {
            Log::info('Store or update AttributeService - problem', ['data' => $exception]);
        }
    }

    /**
     * Save or Update attribute to DB
     *
     * @param string $attributeKey
     * @param array $attributeData
     */
    protected function attribute(string $attributeKey, array $attributeData): void
    {
        foreach ($attributeData as $item) {
            try {
                $item = $this->attributeValidator->validate($item);
                $data = [
                    'id' => $item['id'],
                    'name' => html_entity_decode($item['name']),
                ];
                $updateModel = (self::ATTRIBUTEMODEL[$attributeKey])::find($data['id']);
                if ($updateModel) {
                    $updateModel->update($data);
                } else {
                    (self::ATTRIBUTEMODEL[$attributeKey])::create($data);
                }
            } catch (Throwable) {
                Log::info($attributeKey . ' error create/update');
            }
        }
    }
}
