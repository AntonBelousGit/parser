<?php

declare(strict_types=1);

namespace App\Services\ParserService;

use App\Services\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParserService\Contracts\DominoParseServiceContract;
use DiDom\Document;
use Throwable;

class DominoParseService implements DominoParseServiceContract, DominoParseServiceAttributeContract
{
    private const URL = 'https://dominos.ua/uk/chornomorsk/';
    private const PRODUCT_ATTRIBUTE = 'sizes';
    private const PRODUCT_RELATION_ATTRIBUTE = 'flavors';
    private const PRODUCT_TOPPING = 'toppings';

    /**
     * @return Document
     */
    public function callConnectToParse(): Document
    {
        return new Document(self::URL, true);
    }

    /**
     *Parse get data - return prepare data
     * @return array
     */
    public function parseProduct(): array
    {
        try {
            $html = $this->callConnectToParse();
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
        $product_collection = collect($new['data']['groups'])->pluck('products')->toArray();
        return call_user_func_array('array_merge', $product_collection);
    }

    /**
     * Prepare parsed attribute data
     * @param array $array
     * @return Attribute
     */
    public function parseAttribute(array $array = []): Attribute
    {
        $productAttribute = $array[0][self::PRODUCT_ATTRIBUTE] ?? [];
        $productRelationAttribute = $array[0][self::PRODUCT_RELATION_ATTRIBUTE] ?? [];
        $productTopping = [];
        $temp_arr = [];

        foreach ($array as $product) {
            $temp_arr[] = $product[self::PRODUCT_TOPPING] ?? [];
        }
        if (!empty(array_filter($temp_arr))) {
            $productTopping = $this->array_unique_key(call_user_func_array('array_merge', $temp_arr), 'id');
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
    protected function array_unique_key($array, $key): array
    {
        $tmp = $key_array = array();
        $i = 0;

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $tmp[$i] = $val;
            }
            $i++;
        }
        return $tmp;
    }
}
