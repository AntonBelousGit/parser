<?php

declare(strict_types=1);

namespace App\Services\ParseDomino\ParserService;

use App\Services\BaseServices\Attribute;
use App\Services\BaseServices\Flavor;
use App\Services\BaseServices\Product;
use App\Services\BaseServices\ProductSize;
use App\Services\BaseServices\Size;
use App\Services\BaseServices\Topping;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
use App\Services\ParseDomino\ProductService\Contracts\ProductValidatorContract;
use DiDom\Document;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Throwable;

class DominoParseService implements DominoParseServiceContract, DominoParseServiceAttributeContract
{
    protected array $products = [];

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
        } catch (Throwable $exception) {
            report($exception);
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
                report('DominoParser - parseProduct - parseJsonOptionsSize error');
            }
            try {
                $topping = $this->parseTopping($item['toppings']);
            } catch (Throwable) {
                report('DominoParser - parseProduct - parseTopping error');
            }
            try {
                $this->products[] = new Product(
                    id: $item['id'],
                    name: html_entity_decode($item['name']),
                    image: $item['image'],
                    imageMobile: $item['image_mobile'],
                    topping: new Topping(
                        topping: $topping
                    ),
                    sizes: new Size(
                        size: $attribute['size']
                    ),
                    flavors: new Flavor(
                        flavor: $attribute['flavor']
                    ),
                    attribute: new ProductSize(
                        attribute: $attribute['attribute'],
                    )
                );
            } catch (Throwable) {
                report('DominoParser - parseProduct - new Product error');
            }
        }
        return $this->products;
    }

    /**
     * Prepare parsed attribute data
     * @param array $array
     * @param array $attribute
     * @return Attribute
     */
    public function parseAttribute(array $array = [], array $attribute = []): Attribute
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

            $attrSize = $this->arrayUniqueKey(call_user_func_array('array_merge', $tempArrSize), 'id');
            $attrTopping = $this->arrayUniqueKey(call_user_func_array('array_merge', $tempArrTopping), 'id');
            $attrFlavor = $this->arrayUniqueKey(call_user_func_array('array_merge', $tempArrFlavor), 'id');
        } catch (Throwable) {
            report('DominoParser - parseAttribute - size error');
        }
        return new Attribute(
            size: $attrSize,
            productRelation: $attrFlavor,
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

    /**
     * Remove non-unique key from deep array
     * @param $array
     * @param $key
     * @return array
     */
    protected function arrayUniqueKey($array, $key): array
    {
        $tmp = $keyArray = array();
        $i = 0;

        foreach ($array as $val) {
            if (!in_array($val[$key], $keyArray)) {
                $keyArray[$i] = $val[$key];
                $tmp[$i] = $val;
            }
            $i++;
        }
        return $tmp;
    }
}
