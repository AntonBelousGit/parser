<?php

declare(strict_types=1);

namespace App\Services\ParseZharPizza\ParserService;

use App\Services\BaseServices\Attribute;
use App\Services\BaseServices\Flavor;
use App\Services\BaseServices\Product;
use App\Services\BaseServices\ProductSize;
use App\Services\BaseServices\Size;
use App\Services\BaseServices\Topping;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceAttributeContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaProductValidatorContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Throwable;

class ZharPizzaParseService implements ZharPizzaParseServiceContract, ZharPizzaParseServiceAttributeContract
{
    /**
     * @var array
     */
    protected array $products = [];

    /**
     * ZharPizzaParseService constructor.
     * @param ZharPizzaProductValidatorContract $productValidator
     */
    public function __construct(
        protected ZharPizzaProductValidatorContract $productValidator,
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
     */
    public function parseProduct(string $address): array
    {
        try {
            $productsParse = $this->callConnectToParse($address);
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
                    image: $image,
                    imageMobile: $image,
                    topping: new Topping(
                        topping: $topping
                    ),
                    sizes: new Size($attribute),
                    flavors: new Flavor(),
                    attribute: new ProductSize(
                        attribute: [
                            ['size_id' => $attribute[0]['id'] ?? '35-sm', 'flavor_id' => '', 'price' => (float)$item['price']]
                        ],
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
                $tempArrSize[] = $item->sizes->size;
                $tempArrTopping[] = $item->topping->topping;
            }
            $attrSize = arrayUniqueKey(call_user_func_array('array_merge', $tempArrSize), 'id');
            $attrTopping = arrayUniqueKey(call_user_func_array('array_merge', $tempArrTopping), 'id');
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
}
