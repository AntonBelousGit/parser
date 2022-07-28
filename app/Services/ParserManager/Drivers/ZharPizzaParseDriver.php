<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductAttributeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ZharPizzaParseDriver extends BaseDriver
{
    /**
     * ZharPizzaParseDriver constructor.
     *
     * @param ParseValidatorContract $parseValidatorContract
     */
    public function __construct(
        protected ParseValidatorContract $parseValidatorContract,
    ) {
    }

    /**
     *Parse get data - return prepare data
     *
     * @param string $url
     * @return ParserProductDataDTO
     */
    public function parseProduct(string $url): ParserProductDataDTO
    {
        $productsParse = json_decode($this->getHtml($url));
        $collectSize = collect();
        $collectTopping = collect();
        $products = collect();
        foreach ($productsParse->products as $product) {
            $product = $this->parseValidatorContract->validate(collect($product)->toArray(), $this->validationRules());
            $image = (json_decode($product['gallery']));
            $toppings = $this->parseJsonTopping($product['descr']);
            $attributes = $this->parseJsonSize($product['json_options']);
            $products->push(new ProductDTO(
                id: $product['uid'],
                name: $product['title'],
                url: $url,
                images: $image,
                imagesMobile: $image,
                toppings: $toppings,
                sizes: $attributes,
                flavors: collect(),
                attributes: new ProductAttributeDTO(
                    attributes: collect([['size_id' => $attributes[0]->id ?? '35-sm', 'flavor_id' => '', 'topping_id' => '', 'price' => (float)$product['price']]]),
                )
            ));
            $collectSize->push($attributes);
            $collectTopping->push($toppings);
        }
        $parseAttribute = $this->parseAttribute($collectSize, $collectTopping);

        return new ParserProductDataDTO(
            products: $products,
            attributes: $parseAttribute,
        );
    }

    /**
     * Prepare parsed attribute data
     *
     * @param Collection $size
     * @param Collection $topping
     * @return AttributeDTO
     */
    public function parseAttribute(Collection $size, Collection $topping): AttributeDTO
    {
        return new AttributeDTO(
            sizes: $this->removeDuplicates($size->flatten(1), 'id'),
            flavors: collect(),
            toppings: $this->removeDuplicates($topping->flatten(1), 'id')
        );
    }

    /**
     * Parse attribute topping from json
     *
     * @param string $data
     * @return Collection
     */
    protected function parseJsonTopping(string $data): Collection
    {
        $tempCollect = collect();
        $array = array_map('trim', explode(',', $data));
        foreach ($array as $item) {
            $tempCollect->push(new ToppingDTO(id: Str::slug($item), name: Str::ucfirst($item)));
        }

        return $tempCollect;
    }

    /**
     *Parse attribute size from json
     *
     * @param string $data
     * @return Collection
     */
    protected function parseJsonSize(string $data): Collection
    {
        if ($data) {
            $data = json_decode($data);
            $tempCollect = collect();
            foreach ($data[0]->values as $size) {
                $tempCollect->push(new SizeDTO(id:Str::slug($size), name:$size));
            }

            return $tempCollect;
        }

        return collect([new SizeDTO(id:'standard', name:'Standard')]);
    }

    /**
     * Validation rulers
     *
     * @return string[][]
     */
    protected function validationRules(): array
    {
        return [
            'uid' => ['required','string','max:50'],
            'title' => ['required', 'string','max:50'],
            'price' => ['required', 'string','max:50'],
            'descr' => ['required', 'string'],
            'gallery' => ['required', 'string'],
            'json_options' => ['nullable', 'string'],
        ];
    }
}
