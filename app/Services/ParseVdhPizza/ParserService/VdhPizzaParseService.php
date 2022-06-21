<?php

declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ParserService;

use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceAttributeContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaProductValidatorContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Throwable;

class VdhPizzaParseService implements VdhPizzaParseServiceContract, VdhPizzaParseServiceAttributeContract
{
    private const URL = 'https://store.tildacdn.com/api/getproductslist/?storepartuid=608315548424&recid=155887892&c=1655719066017&getparts=true&getoptions=true&slice=1&&size=36';

    protected array $products = [];

    public function __construct(
        protected VdhPizzaProductValidatorContract $productValidator,
    ) {
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function callConnectToParse(): mixed
    {
        $client = new Client();
        $body = $client->get(self::URL)->getBody();
        return json_decode((string)$body);
    }

    /**
     *Parse get data - return prepare data
     * @return array
     * @throws GuzzleException
     */
    public function parseProduct(): array
    {
        $productsParse = $this->callConnectToParse();
        try {
            foreach ($productsParse->products as $item) {
                $item = $this->productValidator->validate(collect($item)->toArray());
                $topping = [];
                $image = (json_decode($item['gallery']));
                try {
                    $topping = $this->parseJsonTopping($item['descr']);
                } catch (Throwable) {
                    report('VdhPizzaParser - parseProduct - parseTopping error');
                }
                $this->products[] = new Product(
                    id: $item['uid'],
                    name: $item['title'],
                    image: $image[0]->img,
                    topping: new Topping(
                        topping: $topping
                    ),
                    attribute: new ProductSize(
                        attribute: [
                            ['id'=> 'standard',"name" => "Standard"]
                        ],
                        price: (float)$item['price']
                    )
                );
            }
        } catch (Throwable) {
            report('VdhPizzaParser - parseProduct error');
        }
        return $this->products;
    }

    /**
     * Prepare parsed attribute data
     * @param array $array
     * @return Attribute
     */
    public function parseAttribute(array $array = []): Attribute
    {
        $tempArrTopping = [];
        $attrTopping = [];

        try {
            foreach ($array as $item) {
                $tempArrTopping[] = $item->topping->topping;
            }
            $attrTopping = $this->arrayUniqueKey(call_user_func_array('array_merge', $tempArrTopping), 'id');
        } catch (Throwable) {
            report('VdhPizzaParser - parseAttribute - size error');
        }

        return new Attribute(
            size: [['id'=> 'standard',"name" => "Standard"]],
            topping: $attrTopping
        );
    }

    /**
     * @param $data
     * @return array
     */
    protected function parseJsonTopping($data): array
    {
        $tempArray = [];
        $array = array_map('trim', explode(',', $data));
        foreach ($array as $item) {
            $cleanValueHtml = trim(strip_tags($item));
            $tempArray[] = [ 'id' =>Str::slug($cleanValueHtml), 'name' =>$cleanValueHtml ];
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
