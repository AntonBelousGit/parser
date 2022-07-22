<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use App\Services\ParserManager\Contracts\ParseDriverContract;
use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class VdhPizzaParseDriver implements ParseDriverContract
{
    /**
     * All products
     *
     * @var array
     */
    protected array $products = [];

    /**
     * VdhPizzaParseDriver constructor.
     *
     * @param ParseValidatorContract $parseValidatorContract
     * @param ConnectToParseServiceContract $parseServiceContract
     */
    public function __construct(
        protected ParseValidatorContract $parseValidatorContract,
        protected ConnectToParseServiceContract $parseServiceContract,
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
        $productsParse = json_decode($this->parseServiceContract->connect($url));
        $collectTopping = collect();
        foreach ($productsParse->products as $item) {
            $item = $this->parseValidatorContract->validate(collect($item)->toArray(), $this->validationRules());
            $image = (json_decode($item['gallery']));
            $topping = $this->parseJsonTopping($item['descr']);
            $this->products[] = new ProductDTO(
                id: $item['uid'],
                name: $item['title'],
                image: $image,
                imageMobile: $image,
                topping: $topping,
                sizes: collect(),
                flavors: collect(),
                attribute: new ProductSizeDTO(
                    attribute: collect([['size_id' => 'standard', 'flavor_id' => '', 'price' => (float)$item['price']]]),
                )
            );
            $collectTopping->push($topping);
        }
        $parseAttribute = $this->parseAttribute($collectTopping);
        return new ParserProductDataDTO(
            products: $this->products,
            attributes: $parseAttribute,
        );
    }

    /**
     * Prepare parsed attribute data
     *
     * @param array $array
     * @return AttributeDTO
     */
    public function parseAttribute(Collection $topping): AttributeDTO
    {
        $attrTopping = collect();
        $attrTopping->push(collectionUniqueKey($topping->flatten(1), 'id'));
        return new AttributeDTO(
            size: collect([new SizeDTO(id:'standard', name: 'Standard')]),
            flavor: collect(),
            topping: $attrTopping
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
