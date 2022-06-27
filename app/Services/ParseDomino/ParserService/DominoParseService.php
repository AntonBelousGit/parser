<?php

declare(strict_types=1);

namespace App\Services\ParseDomino\ParserService;

use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
use DiDom\Document;
use Illuminate\Support\Arr;
use Throwable;

class DominoParseService implements DominoParseServiceContract, DominoParseServiceAttributeContract
{
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
        return call_user_func_array('array_merge', $productArray);
    }

    /**
     * Prepare parsed attribute data
     * @param array $array
     * @param array $attribute
     * @return Attribute
     */
    public function parseAttribute(array $array = [], array $attribute = []): Attribute
    {
        $productAttribute = $array[0][$attribute[0]] ?? [];
        $productRelationAttribute = $array[0][$attribute[1]] ?? [];
        $productTopping = [];
        $tempArr = [];

        foreach ($array as $product) {
            $tempArr[] = $product[$attribute[2]] ?? [];
        }
        if (!empty(array_filter($tempArr))) {
            $productTopping = $this->arrayUniqueKey(call_user_func_array('array_merge', $tempArr), 'id');
        }
        return new Attribute(
            size: $productAttribute,
            productRelation: $productRelationAttribute,
            topping: $productTopping
        );
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
