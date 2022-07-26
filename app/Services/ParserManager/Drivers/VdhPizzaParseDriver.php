<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class VdhPizzaParseDriver extends BaseDriver
{
    /**
     * VdhPizzaParseDriver constructor.
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
        $collectTopping = collect();
        $products = collect();
        foreach ($productsParse->products as $item) {
            $item = $this->parseValidatorContract->validate(collect($item)->toArray(), $this->validationRules());
            $image = (json_decode($item['gallery']));
            $topping = $this->parseJsonTopping($item['descr']);
            $products->push(new ProductDTO(
                id: $item['uid'],
                name: $item['title'],
                images: $image,
                imagesMobile: $image,
                toppings: $topping,
                sizes: collect(),
                flavors: collect(),
                attributes: new ProductSizeDTO(
                    attributes: collect([['size_id' => 'standard', 'flavor_id' => '', 'price' => (float)$item['price']]]),
                )
            ));
            $collectTopping->push($topping);
        }
        $parseAttribute = $this->parseAttribute($collectTopping);

        return new ParserProductDataDTO(
            products: $products,
            attributes: $parseAttribute,
        );
    }

    /**
     * Prepare parsed attribute data
     *
     * @param Collection $topping
     * @return AttributeDTO
     */
    public function parseAttribute(Collection $topping): AttributeDTO
    {
        $attrTopping = $this->removeDuplicates($topping->flatten(1), 'id');

        return new AttributeDTO(
            sizes: collect([new SizeDTO(id:'standard', name: 'Standard')]),
            flavors: collect(),
            toppings: $attrTopping
        );
    }

    /**
     * Parse attribute topping from json
     *
     * @param $data
     * @return Collection
     */
    protected function parseJsonTopping($data): Collection
    {
        $tempCollect = collect();
        $array = array_map('trim', explode(',', $data));
        foreach ($array as $item) {
            $cleanValueHtml = trim(strip_tags($item));
            $tempCollect->push(new ToppingDTO(id:Str::slug($cleanValueHtml), name:$cleanValueHtml));
        }

        return $tempCollect;
    }

    /**
     * Validation rulers
     * @return string[][]
     */
    protected function validationRules(): array
    {
        return [
            'uid' => ['required','string','max:50'],
            'title' => ['required', 'string'],
            'price' => ['required', 'string'],
            'descr' => ['required', 'string'],
            'gallery' => ['required', 'string'],
        ];
    }
}
