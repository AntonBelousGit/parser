<?php

declare(strict_types=1);

namespace App\Services\ParserService;


use App\Services\ParserService\Contracts\ParseServiceAttributeContract;
use App\Services\ParserService\Contracts\ParseServiceContract;
use DiDom\Document;
use Throwable;

class ParseService implements ParseServiceContract, ParseServiceAttributeContract
{

    /**
     * @return Document
     */
    public function callConnectToParse(): Document
    {
        return new Document(config('services.parser.url'), true);
    }

    /**
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
        $products = call_user_func_array('array_merge', $product_collection);

        return $products;
    }

    /**
     * @param array $array
     * @return array
     */
    public function parseAttribute(array $array = []): array
    {
        $data = [];
        if (!empty($array)) {
            $data[config('services.parser.product_attribute')] = $array[0][config('services.parser.product_attribute')] ?? '';
            $data[config('services.parser.product_relations_attribute')] = $array[0][config('services.parser.product_relations_attribute')] ?? '';
            $data[config('services.parser.product_topping')] = [];
            $temp_arr = [];

            foreach ($array as $product) {
                $temp_arr[] = $product[config('services.parser.product_topping')];
            }
            if (!empty($temp_arr)) {
                $data[config('services.parser.product_topping')] = $this->array_unique_key(call_user_func_array('array_merge', $temp_arr), 'id');
            }
        }


        return $data;
    }


    protected function array_unique_key($array, $key)
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



