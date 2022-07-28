<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\FlavorDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductAttributeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DominoParseDriver extends BaseDriver
{
    /**
     * DominoParseDriver constructor.
     * @param ParseValidatorContract $parseValidatorContract
     */
    public function __construct(
        protected ParseValidatorContract $parseValidatorContract,
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
        $xml = new Document($this->getHtml($url));
        $parsedData = $this->prepareParsedProducts($xml);
        $products = collect();
        $collectSize = collect();
        $collectTopping = collect();
        $collectFlavor = collect();
        foreach ($parsedData as $product) {
            $product = $this->parseValidatorContract->validate($product, $this->validationRules());
            $attributes = $this->parseSize($product['sizes']);
            $topping = $this->parseTopping($product['toppings']);
            $products->push(new ProductDTO(
                id: $product['link'],
                name: html_entity_decode($product['name']),
                images: $product['image'],
                imagesMobile: $product['image_mobile'],
                toppings: $topping,
                sizes: $attributes['size'],
                flavors: $attributes['flavor'],
                attributes: new ProductAttributeDTO(
                    attributes: $attributes['attribute'],
                )
            ));
            $collectSize->push($attributes['size']);
            $collectTopping->push($topping);
            $collectFlavor->push($attributes['flavor']);
        }
        $parseAttribute = $this->parseAttribute($collectSize, $collectTopping, $collectFlavor);

        return new ParserProductDataDTO(
            products: $products,
            attributes: $parseAttribute,
        );
    }

    /**
     * Prepare products before parse
     *
     * @param Document $xml
     * @return array
     * @throws InvalidSelectorException
     */
    protected function prepareParsedProducts(Document $xml): array
    {
        $stringRawHtml = $xml->find('script');
        $stringHtml = $stringRawHtml[8]->text();
        $array = explode("'", ($stringHtml));
        $str = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $array[1]);
        $new = json_decode($str, true, 100);
        $parsedData = Arr::pluck($new['data']['groups'], 'products');
        return call_user_func_array('array_merge', $parsedData);
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
        return new AttributeDTO(
            sizes: $this->removeDuplicates($size->flatten(1), 'id'),
            flavors: $this->removeDuplicates($flavor->flatten(1), 'id'),
            toppings: $this->removeDuplicates($topping->flatten(1), 'id')
        );
    }

    /**
     * Parse attribute size
     *
     * @param array $data
     * @return Collection
     */
    protected function parseSize(array $data): Collection
    {
        $tempCollection = collect(['size' => collect(), 'flavor' => collect(), 'attribute' => collect()]);
        foreach ($data as $size) {
            $tempCollection['size']->push(new SizeDTO(id: $size['id'], name: html_entity_decode($size['name'])));
            foreach ($size['flavors'] as $flavor) {
                $tempCollection['flavor']->push(new FlavorDTO(id: $flavor['id'], name: html_entity_decode($flavor['name'])));
                $tempCollection['attribute']->push([
                    'size_id' => $size['id'],
                    'flavor_id' => $flavor['id'],
                    'topping_id' => '',
                    'price' => $flavor['product']['price']
                ]);
            }
        }

        return $tempCollection;
    }

    /**
     * Parse attribute topping
     *
     * @param array $data
     * @return Collection
     */
    protected function parseTopping(array $data): Collection
    {
        $tempCollect = collect();
        foreach ($data as $topping) {
            $name = html_entity_decode($topping['name']);
            $tempCollect->push(new ToppingDTO(id: Str::slug($name), name: $name));
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
            'link' => ['required', 'string', 'max:200'],
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
