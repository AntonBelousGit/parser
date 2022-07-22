<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use App\Services\ParserManager\Contracts\ParseDriverContract;
use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\FlavorDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class DominoParseDriver implements ParseDriverContract
{
    /**
     * All products
     *
     * @var array
     */
    protected array $products = [];

    /**
     * DominoParseService constructor.
     * @param ParseValidatorContract $parseValidatorContract
     * @param ConnectToParseServiceContract $parseServiceContract
     */
    public function __construct(
        protected ParseValidatorContract $parseValidatorContract,
        protected ConnectToParseServiceContract $parseServiceContract,
    ) {
    }

    /**
     * Parse get data - return prepare data
     *
     * @param string $url
     * @return ParserProductDataDTO
     * @throws InvalidSelectorException
     */
    public function parseProduct(string $url): ParserProductDataDTO
    {
        $html = new Document($this->parseServiceContract->connect($url));
        $stringRawHtml = $html->find('script');
        $stringHtml = $stringRawHtml[8]->text();
        $array = explode("'", ($stringHtml));
        $str = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $array[1]);
        $new = json_decode($str, true, 100);
        $productArray = Arr::pluck($new['data']['groups'], 'products');
        $products = call_user_func_array('array_merge', $productArray);

        $collectSize = collect();
        $collectTopping = collect();
        $collectFlavor = collect();
        foreach ($products as $item) {
            $item = $this->parseValidatorContract->validate($item, $this->validationRules());
            $attribute = $this->parseSize($item['sizes']);
            $topping = $this->parseTopping($item['toppings']);
            $this->products[] = new ProductDTO(
                id: $item['id'],
                name: html_entity_decode($item['name']),
                image: $item['image'],
                imageMobile: $item['image_mobile'],
                topping: $topping,
                sizes: $attribute['size'],
                flavors: $attribute['flavor'],
                attribute: new ProductSizeDTO(
                    attribute: $attribute['attribute'],
                )
            );
            $collectSize->push($attribute['size']);
            $collectTopping->push($topping);
            $collectFlavor->push($attribute['flavor']);
        }
        $parseAttribute = $this->parseAttribute($collectSize, $collectTopping, $collectFlavor);

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
     * @param Collection $flavor
     * @return AttributeDTO
     */
    protected function parseAttribute(Collection $size, Collection $topping, Collection $flavor): AttributeDTO
    {
        $attrSize = collect();
        $attrTopping = collect();
        $attrFlavor = collect();
        $attrSize->push(collectionUniqueKey($size->flatten(1), 'id'));
        $attrTopping->push(collectionUniqueKey($topping->flatten(1), 'id'));
        $attrFlavor->push(collectionUniqueKey($flavor->flatten(1), 'id'));

        return new AttributeDTO(
            size: $attrSize,
            flavor: $attrFlavor,
            topping: $attrTopping
        );
    }

    /**
     * Parse attribute size
     *
     * @param $data
     * @return Collection
     */
    protected function parseSize($data): Collection
    {
        $tempCollection = collect(['size' => collect(), 'flavor' => collect(), 'attribute' => collect()]);
        foreach ($data as $size) {
            $tempCollection['size']->push(new SizeDTO(id: $size['id'], name: html_entity_decode($size['name'])));
            foreach ($size['flavors'] as $flavor) {
                $tempCollection['flavor']->push(new FlavorDTO(id: $flavor['id'], name: html_entity_decode($flavor['name'])));
                $tempCollection['attribute']->push([
                    'size_id' => $size['id'],
                    'flavor_id' => $flavor['id'],
                    'price' => $flavor['product']['price']
                ]);
            }
        }

        return $tempCollection;
    }

    /**
     * Parse attribute topping
     *
     * @param $data
     * @return Collection
     */
    protected function parseTopping($data): Collection
    {
        $tempCollect = collect();
        foreach ($data as $item) {
            $tempCollect->push(new ToppingDTO(id: $item['id'], name: html_entity_decode($item['name'])));
        }

        return $tempCollect;
    }

    /**
     * Validation rulers
     *
     * @return string[][]
     */
    protected function validationRules(): array
    {
        return [
            'id' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:200'],
            'image' => ['required', 'array', 'min:1'],
            'image.*' => ['required'],
            'image_mobile' => ['required', 'array', 'min:1'],
            'image_mobile.*' => ['required'],
            'toppings.*.id' => ['required', 'string', 'max:50'],
            'toppings.*.name' => ['required', 'string', 'max:200'],
            'sizes.*.id' => ['required', 'string', 'max:50'],
            'sizes.*.name' => ['required', 'string', 'max:200'],
            'sizes.*.flavors.*.id' => ['required', 'string', 'max:50'],
            'sizes.*.flavors.*.name' => ['required', 'string', 'max:200'],
            'sizes.*.flavors.*.product.price' => ['required', 'integer'],
        ];
    }
}
