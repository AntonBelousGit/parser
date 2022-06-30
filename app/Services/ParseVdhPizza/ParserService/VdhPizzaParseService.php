<?php

declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ParserService;

use App\Services\BaseServices\Attribute;
use App\Services\BaseServices\Flavor;
use App\Services\BaseServices\Product;
use App\Services\BaseServices\ProductSize;
use App\Services\BaseServices\Size;
use App\Services\BaseServices\Topping;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceAttributeContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaProductValidatorContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Throwable;

class VdhPizzaParseService implements VdhPizzaParseServiceContract, VdhPizzaParseServiceAttributeContract
{
    protected array $products = [];

    public function __construct(
        protected VdhPizzaProductValidatorContract $productValidator,
    ) {
    }

    /**
     * @param string $address
     * @return mixed
     * @throws GuzzleException
     */
    public function callConnectToParse(string $address): mixed
    {
        $client = new Client();
        $body = $client->get($address)->getBody();
        return json_decode((string)$body);
    }

    /**
     *Parse get data - return prepare data
     * @param string $address
     * @return array
     * @throws GuzzleException
     */
    public function parseProduct(string $address): array
    {
        $productsParse = $this->callConnectToParse($address);
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
                    image: [$image[0]->img],
                    imageMobile: [$image[0]->img],
                    topping: new Topping(
                        topping: $topping
                    ),
                    sizes: new Size(['id'=> 'standard',"name" => "Standard"]),
                    flavors: new Flavor(),
                    attribute: new ProductSize(
                        attribute: [
                            ['size_id'=> 'standard', 'flavor_id' => '','price'=> (float)$item['price']]
                        ],
                    )
                );
            }
        } catch (Throwable $exception) {
            report('VdhPizzaParser - parseProduct error'. $exception);
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
