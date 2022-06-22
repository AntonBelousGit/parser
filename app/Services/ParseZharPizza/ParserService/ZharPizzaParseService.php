<?php

declare(strict_types=1);

namespace App\Services\ParseZharPizza\ParserService;

use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceAttributeContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaProductValidatorContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Throwable;

class ZharPizzaParseService implements ZharPizzaParseServiceContract, ZharPizzaParseServiceAttributeContract
{
    protected array $products = [];

    public function __construct(
        protected ZharPizzaProductValidatorContract $productValidator,
    ) {
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function callConnectToParse(): mixed
    {
        $client = new Client();
        $body = $client->get(config('services.parse.zharPizzaParse'))->getBody();
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
                $attribute = [];
                $image = (json_decode($item['gallery']));
                try {
                    $topping = $this->parseJsonTopping($item['descr']);
                } catch (Throwable) {
                    report('ZharPizzaParser - parseProduct - parseTopping error');
                }
                try {
                    $attribute = $this->parseJsonOptionsSize($item['json_options']);
                } catch (Throwable) {
                    report('ZharPizzaParser - parseProduct - parseJsonOptionsSize error');
                }
                $this->products[] = new Product(
                    id: $item['uid'],
                    name: $item['title'],
                    image: $image[0]->img,
                    topping: new Topping(
                        topping: $topping
                    ),
                    attribute: new ProductSize(
                        attribute: $attribute,
                        price: (float)$item['price']
                    )
                );
            }
        } catch (Throwable) {
            report('ZharPizzaParser - parseProduct error');
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
        $tempArrSize = [];
        $tempArrTopping = [];
        $attrSize = [];
        $attrTopping = [];

        try {
            foreach ($array as $item) {
                $tempArrSize[] = $item->attribute->attribute;
                $tempArrTopping[] = $item->topping->topping;
            }
            $attrSize = $this->arrayUniqueKey(call_user_func_array('array_merge', $tempArrSize), 'id');
            $attrTopping = $this->arrayUniqueKey(call_user_func_array('array_merge', $tempArrTopping), 'id');
        } catch (Throwable) {
            report('ZharPizzaParser - parseAttribute - size error');
        }

        return new Attribute(
            size: $attrSize,
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
            $tempArray[] = ['id' => Str::slug($item), 'name' => $item];
        }
        return $tempArray;
    }

    /**
     * @param $data
     * @return array
     */
    protected function parseJsonOptionsSize($data): array
    {
        $data = json_decode($data);
        $tempArray = [];

        foreach ($data[0]->values as $item) {
            $tempArray[] = ['id' => Str::slug($item), 'name' => $item];
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
