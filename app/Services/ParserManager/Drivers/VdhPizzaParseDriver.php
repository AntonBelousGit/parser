<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use App\Services\ParserManager\Contracts\ParseDriverContract;
use App\Services\ParserManager\Contracts\ParseManagerAttributeDriver;
use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class VdhPizzaParseDriver implements ParseDriverContract, ParseManagerAttributeDriver
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
     * @param string $method
     * @return array
     */
    public function parseProduct(string $url, string $method): array
    {
        try {
            $productsParse = json_decode($this->parseServiceContract->$method($url));
            dd($productsParse);
            foreach ($productsParse->products as $item) {
                $item = $this->parseValidatorContract->validate(collect($item)->toArray(), $this->validationRules());
                $topping = collect();
                $image = (json_decode($item['gallery']));
                try {
                    $topping = $this->parseJsonTopping($item['descr']);
                } catch (Throwable) {
                    Log::info('VdhPizzaParser - parseProduct - parseTopping error');
                }
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
            }
        } catch (Throwable) {
            Log::info('VdhPizzaParser - parseProduct error');
        }
        return $this->products;
    }

    /**
     * Prepare parsed attribute data
     *
     * @param array $array
     * @return AttributeDTO
     */
    public function parseAttribute(array $array = []): AttributeDTO
    {
        $tempCollectTopping = collect();
        $attrTopping = collect();
        try {
            foreach ($array as $item) {
                $tempCollectTopping->push($item->topping);
            }
            $attrTopping->push(collectionUniqueKey($tempCollectTopping->flatten(1), 'id'));
        } catch (Throwable) {
            Log::info('VdhPizzaParser - parseAttribute - error');
        }

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
