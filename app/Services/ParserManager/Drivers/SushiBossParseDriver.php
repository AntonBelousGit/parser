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
use DiDom\Element;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SushiBossParseDriver extends BaseDriver
{
    /**
     * DominoParseService constructor.
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
        $parsedData = $xml->find('.product-layout');
        $products = collect();
        $collectSize = collect();
        $collectTopping = collect();
        $collectFlavor = collect();

        foreach ($parsedData as $product) {
            $product = $this->prepareParsedProduct($product);
            $product = $this->parseValidatorContract->validate($product, $this->validationRules());
            $attributes = $this->prepareAttribute($product['sizes'], $product['flavors'], $product['toppings']);
            $products->push(new ProductDTO(
                id: $product['id'],
                name: $product['name'],
                url: $url,
                images: $product['image'],
                imagesMobile: $product['image'],
                toppings: $product['toppings'],
                sizes: $product['sizes'],
                flavors: $product['flavors'],
                attributes: new ProductAttributeDTO(
                    attributes: $attributes['attribute'],
                )
            ));
            $collectSize->push($product['sizes']);
            $collectTopping->push($product['toppings']);
            $collectFlavor->push($product['flavors']);
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
     * @param Element $xml
     * @return array
     * @throws InvalidSelectorException
     */
    protected function prepareParsedProduct(Element $xml): array
    {
        $size = collect();
        $flavor = collect();
        $topping = collect();
        $image = [$xml->first('img')->attr('src')];
        $name = $xml->first('.us-module-title > a')->text();
        $sizeTemp = $xml->first('.options-category > div > div')?->find('.radio-inline> label');
        if ($sizeTemp) {
            $size = $this->parseSize($sizeTemp);
        } else {
            $price = $xml->first('.us-module-price-actual > span')->attr('data-price');
            $size->push(new SizeDTO(id: 'serednia', name: 'Середня', price: (float)$price));
        }

        $flavorTemp = $xml->find('.options-category > div');
        $flavorTemp = isset($flavorTemp[1]) ? $flavorTemp[1]->find('select > option') : null;
        if ($flavorTemp) {
            unset($flavorTemp[0]);
            $flavor = $this->parseFlavor($flavorTemp);
        }
        $toppingTemp = $xml->find('.options-category > div');
        $toppingTemp = !empty(end($toppingTemp)) ? end($toppingTemp)->find('select > option') : null;

        if ($toppingTemp) {
            unset($toppingTemp[0]);
            $topping = $this->parseTopping($toppingTemp);
        }

        return ['id' => Str::slug($name), 'image' => $image, 'name' => $name, 'sizes' => $size, 'flavors' => $flavor, 'toppings' => $topping];
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
     * Prepare product attribute relations
     *
     * @param Collection $sizes
     * @param Collection $flavors
     * @param Collection $toppings
     * @return Collection
     */
    protected function prepareAttribute(Collection $sizes, Collection $flavors, Collection $toppings): Collection
    {
        $tempCollection = collect(['attribute' => collect()]);
        foreach ($sizes as $size) {
            if ($toppings->isNotEmpty()) {
                foreach ($toppings as $topping) {
                    if ($flavors->isNotEmpty()) {
                        foreach ($flavors as $flavor) {
                            $tempCollection['attribute']->push([
                                'size_id' => $size->id,
                                'flavor_id' => $flavor->id,
                                'topping_id' => $topping->id,
                                'price' => $size->price + $topping->price + $flavor->price
                            ]);
                        }
                    } else {
                        $tempCollection['attribute']->push([
                            'size_id' => $size->id,
                            'flavor_id' => '',
                            'topping_id' => $topping->id,
                            'price' => $size->price + $topping->price
                        ]);
                    }
                }
            } else {
                $tempCollection['attribute']->push([
                    'size_id' => $size->id,
                    'flavor_id' => '',
                    'topping_id' => '',
                    'price' => $size->price
                ]);
            }
        }

        return $tempCollection;
    }

    /**
     * Parse attribute topping
     *
     * @param array $toppingData
     * @return Collection
     */
    protected function parseTopping(array $toppingData): Collection
    {
        $tempCollect = collect();
        foreach ($toppingData as $toppingItem) {
            $name = $toppingItem->text();
            $price = $toppingItem->attr('data-price');
            $name = preg_replace('/ [0-9]{2}г/', '', $name);
            $tempCollect->push(new ToppingDTO(id: Str::slug($name), name: Str::ucfirst($name), price: (float)$price));
        }

        return $tempCollect;
    }

    /**
     * Parse attribute flavor
     *
     * @param array $flavorData
     * @return Collection
     */
    protected function parseFlavor(array $flavorData): Collection
    {
        $tempCollect = collect();
        foreach ($flavorData as $flavorItem) {
            $name = $flavorItem->text();
            $price = $flavorItem->attr('data-price');
            $tempCollect->push(new FlavorDTO(id: Str::slug($name), name: Str::ucfirst($name), price: (float)$price));
        }

        return $tempCollect;
    }

    /**
     * Parse attribute size
     *
     * @param array $sizeData
     * @return Collection
     */
    protected function parseSize(array $sizeData): Collection
    {
        $tempCollect = collect();
        foreach ($sizeData as $sizeItem) {
            $priceSpecial = $sizeItem->find('input')[0]->attr('data-special');
            $price = $sizeItem->find('input')[0]->attr('data-price');
            $name = $sizeItem->find('span')[0]->text();
            if ($priceSpecial) {
                $price = $priceSpecial;
            }
            $tempCollect->push(new SizeDTO(id: Str::slug($name), name: Str::ucfirst($name), price: (float)$price));
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
            'toppings' => ['nullable'],
            'sizes' => ['filled'],
            'flavors' => ['nullable'],
        ];
    }
}
