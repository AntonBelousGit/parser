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
        foreach ($productsParse->products as $product) {
            $product = $this->parseValidatorContract->validate(collect($product)->toArray(), $this->validationRules());
            $image = (json_decode($product['gallery']));
            $topping = $this->parseJsonTopping($product['descr']);
            $products->push(new ProductDTO(
                id: $product['url'],
                name: $product['title'],
                images: $image,
                imagesMobile: $image,
                toppings: $topping,
                sizes: collect(),
                flavors: collect(),
                attributes: new ProductAttributeDTO(
                    attributes: collect([['size_id' => 'standartna', 'flavor_id' => '','topping_id' => '', 'price' => (float)$product['price']]]),
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
        return new AttributeDTO(
            sizes: collect([new SizeDTO(id:'standartna', name: 'Стандартна')]),
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
        foreach ($array as $topping) {
            $cleanValueHtml = trim(strip_tags($topping));
            $tempCollect->push(new ToppingDTO(id: Str::slug($cleanValueHtml), name: Str::ucfirst($cleanValueHtml)));
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
            'url' => ['required','string'],
            'title' => ['required', 'string','max:50'],
            'price' => ['required', 'string','max:50'],
            'descr' => ['required', 'string'],
            'gallery' => ['required', 'string'],
        ];
    }
}
