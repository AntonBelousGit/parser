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
use Illuminate\Support\Str;

class ZharPizzaParseDriver implements ParseDriverContract, ParseManagerAttributeDriver
{
    /**
     * All products
     *
     * @var array
     */
    protected array $products = [];

    /**
     * ZharPizzaParseService constructor.
     *
     * @param ParseValidatorContract $parseValidatorContract
     * @param ConnectToParseServiceContract $parseServiceContract
     */
    public function __construct(
        protected ParseValidatorContract $parseValidatorContract,
        protected ConnectToParseServiceContract $parseServiceContract
    ) {
    }

    /**
     *Parse get data - return prepare data
     *
     * @param string $url
     * @param string $type
     * @return array
     */
    public function parseProduct(string $url, string $type): array
    {
        $productsParse = json_decode($this->parseServiceContract->connect($type, $url));
        foreach ($productsParse->products as $item) {
            $item = $this->parseValidatorContract->validate(collect($item)->toArray(), $this->validationRules());
            $image = (json_decode($item['gallery']));
            $topping = $this->parseJsonTopping($item['descr']);
            $attribute = $this->parseJsonSize($item['json_options']);
            $this->products[] = new ProductDTO(
                id: $item['uid'],
                name: $item['title'],
                image: $image,
                imageMobile: $image,
                topping: $topping,
                sizes: $attribute,
                flavors: collect(),
                attribute: new ProductSizeDTO(
                    attribute: collect([['size_id' => $attribute[0]->id ?? '35-sm', 'flavor_id' => '', 'price' => (float)$item['price']]]),
                )
            );
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
        $tempCollectSize = collect();
        $tempCollectTopping = collect();
        $attrSize = collect();
        $attrTopping = collect();

        foreach ($array as $item) {
            $tempCollectSize->push($item->sizes);
            $tempCollectTopping->push($item->topping);
        }
        $attrSize->push(collectionUniqueKey($tempCollectSize->flatten(1), 'id'));
        $attrTopping->push(collectionUniqueKey($tempCollectTopping->flatten(1), 'id'));

        return new AttributeDTO(
            size: $attrSize,
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
            $tempCollect->push(new ToppingDTO(id:Str::slug($item), name:$item));
        }
        return $tempCollect;
    }

    /**
     *Parse attribute size from json
     *
     * @param $data
     * @return Collection
     */
    protected function parseJsonSize($data): Collection
    {
        if ($data) {
            $data = json_decode($data);
            $tempCollect = collect();
            foreach ($data[0]->values as $item) {
                $tempCollect->push(new SizeDTO(id:Str::slug($item), name:$item));
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
            'title' => ['required', 'string'],
            'price' => ['required', 'string'],
            'descr' => ['required', 'string'],
            'gallery' => ['required', 'string'],
            'json_options' => ['nullable', 'string'],
        ];
    }
}
