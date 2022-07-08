<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\Contracts\ParseDriverContract;
use App\Services\ParserManager\Contracts\ParseServiceAttributeDriver;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\FlavorDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use App\Services\ParserManager\Drivers\ParseDomino\Contracts\ProductValidatorContract;
use DiDom\Document;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class DominoParseDriver implements ParseDriverContract, ParseServiceAttributeDriver
{
    /**
     * @var array
     */
    protected array $products = [];

    /**
     * DominoParseService constructor.
     * @param ProductValidatorContract $productValidator
     */
    public function __construct(
        protected ProductValidatorContract $productValidator,
    ) {
    }

    /**
     * @param string $address
     * @return Document
     */
    public function callConnectToParse(string $address): Document
    {
        return new Document($address, true);
    }

    /**
     *Parse get data - return prepare data
     * @param string $address
     * @return array
     */
    public function parseProduct(string $address): array
    {
        try {
            $html = $this->callConnectToParse($address);
            $stringRawHtml = $html->find('script');
        } catch (Throwable) {
            Log::info('DominoParser - connect error');
            return [];
        }
        $stringHtml = $stringRawHtml[8]->text();
        $array = explode("'", ($stringHtml));
        $str = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $array[1]);
        $new = json_decode($str, true, 100);
        $productArray = Arr::pluck($new['data']['groups'], 'products');
        $products = call_user_func_array('array_merge', $productArray);

        foreach ($products as $item) {
            $item = $this->productValidator->validate($item);
            $attribute = [];
            $topping = [];

            try {
                $attribute = $this->parseOptionsSize($item['sizes']);
            } catch (Throwable) {
                Log::info('DominoParser - parseProduct - parseJsonOptionsSize error');
            }
            try {
                $topping = $this->parseTopping($item['toppings']);
            } catch (Throwable) {
                Log::info('DominoParser - parseProduct - parseTopping error');
            }
            try {
                $this->products[] = new ProductDTO(
                    id: $item['id'],
                    name: html_entity_decode($item['name']),
                    image: $item['image'],
                    imageMobile: $item['image_mobile'],
                    topping: new ToppingDTO(
                        topping: $topping
                    ),
                    sizes: new SizeDTO(
                        size: $attribute['size']
                    ),
                    flavors: new FlavorDTO(
                        flavor: $attribute['flavor']
                    ),
                    attribute: new ProductSizeDTO(
                        attribute: $attribute['attribute'],
                    )
                );
            } catch (Throwable) {
                Log::info('DominoParser - parseProduct - new Product error');
            }
        }
        return $this->products;
    }

    /**
     * Prepare parsed attribute data
     * @param array $array
     * @return AttributeDTO
     */
    public function parseAttribute(array $array = []): AttributeDTO
    {
        $tempArrSize = [];
        $tempArrTopping = [];
        $tempArrFlavor = [];
        $attrSize = [];
        $attrTopping = [];
        $attrFlavor = [];
        try {
            foreach ($array as $item) {
                $tempArrSize[] = $item->sizes->size;
                $tempArrTopping[] = $item->topping->topping;
                $tempArrFlavor[] = $item->flavors->flavor;
            }

            $attrSize = arrayUniqueKey(call_user_func_array('array_merge', $tempArrSize), 'id');
            $attrTopping = arrayUniqueKey(call_user_func_array('array_merge', $tempArrTopping), 'id');
            $attrFlavor = arrayUniqueKey(call_user_func_array('array_merge', $tempArrFlavor), 'id');
        } catch (Throwable $exception) {
            report('DominoParser - parseAttribute - size error' . $exception);
        }
        return new AttributeDTO(
            size: $attrSize,
            flavor: $attrFlavor,
            topping: $attrTopping
        );
    }

    /**
     * @param $data
     * @return array
     */
    protected function parseOptionsSize($data): array
    {
        $tempArray = ['size' => [], 'flavor' => [], 'attribute' => []];
        foreach ($data as $size) {
            $tempArray['size'][] = ['id' => $size['id'], 'name' => html_entity_decode($size['name'])];
            foreach ($size['flavors'] as $flavor) {
                $tempArray['flavor'][] = ['id' => $flavor['id'], 'name' => html_entity_decode($flavor['name'])];
                $tempArray['attribute'][] = [
                    'size_id' => $size['id'],
                    'flavor_id' => $flavor['id'],
                    'price' => $flavor['product']['price']
                ];
            }
        }
        return $tempArray;
    }

    /**
     * @param $data
     * @return array
     */
    protected function parseTopping($data): array
    {
        $tempArray = [];
        foreach ($data as $item) {
            $tempArray[] = ['id' => $item['id'], 'name' => html_entity_decode($item['name'])];
        }
        return $tempArray;
    }
}