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
    private const URL = 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1655380264126&getparts=true&getoptions=true';

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
        $temp_arr_size = [];
        $temp_arr_topping = [];
        $attr_size = [];
        $attr_topping = [];

        try {
            foreach ($array as $item) {
                $temp_arr_size[] = $item->attribute->attribute;
                $temp_arr_topping[] = $item->topping->topping;
            }
            $attr_size = call_user_func_array('array_merge', $temp_arr_size);
            $attr_topping = call_user_func_array('array_merge', $temp_arr_topping);
        } catch (Throwable) {
            report('ZharPizzaParser - parseAttribute - size error');
        }

        return new Attribute(
            size: $attr_size,
            topping: $attr_topping
        );
    }

    /**
     * @param $data
     * @return array
     */
    protected function parseJsonTopping($data): array
    {
        $temp_array = [];
        $array = array_map('trim', explode(',', $data));
        foreach ($array as $item) {
            $temp_array[] = Str::slug($item);
        }
        return array_combine($temp_array, $array);
    }

    /**
     * @param $data
     * @return array
     */
    protected function parseJsonOptionsSize($data): array
    {
        $data = json_decode($data);
        $temp_array = [];

        foreach ($data[0]->values as $item) {
            $temp_array[] = Str::slug($item);
        }
        return array_combine($temp_array, $data[0]->values);
    }
}
