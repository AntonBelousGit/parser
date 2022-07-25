<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ConnectService\Contracts\ConnectServiceContract;
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

class ZharPizzaParseDriver implements ParseDriverContract
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
     * @param ConnectServiceContract $parseServiceContract
     */
    public function __construct(
        protected ParseValidatorContract $parseValidatorContract,
        protected ConnectServiceContract $parseServiceContract
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
        $collectSize = collect();
        $collectTopping = collect();
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
            $collectSize->push($attribute);
            $collectTopping->push($topping);
        }
        $parseAttribute = $this->parseAttribute($collectSize, $collectTopping);

        return new ParserProductDataDTO(
            products: $this->products,
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
        $attrSize = collect();
        $attrTopping = collect();
        $attrSize->push(collectionUniqueKey($size->flatten(1), 'id'));
        $attrTopping->push(collectionUniqueKey($topping->flatten(1), 'id'));

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
