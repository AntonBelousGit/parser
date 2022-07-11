<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\Contracts\ParseDriverContract;
use App\Services\ParserManager\Contracts\ParseServiceAttributeDriver;
use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\FlavorDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ZharPizzaParseDriver implements ParseDriverContract, ParseServiceAttributeDriver
{
    /**
     * @var array
     */
    protected array $products = [];

    /**
     * ZharPizzaParseService constructor.
     * @param ParseValidatorContract $parseValidatorContract
     */
    public function __construct(
        protected ParseValidatorContract $parseValidatorContract,
    ) {
    }

    /**
     * @param string $url
     * @return mixed
     * @throws GuzzleException
     */
    public function callConnectToParse(string $url): mixed
    {
        $client = new Client();
        $body = $client->get($url)->getBody();
        return json_decode((string)$body);
    }

    /**
     *Parse get data - return prepare data
     * @param string $url
     * @return array
     */
    public function parseProduct(string $url): array
    {
        try {
            $productsParse = $this->callConnectToParse($url);
            foreach ($productsParse->products as $item) {
                $item = $this->parseValidatorContract->validate(collect($item)->toArray(), $this->validationRules());
                $topping = [];
                $attribute = [];
                $image = (json_decode($item['gallery']));
                try {
                    $topping = $this->parseJsonTopping($item['descr']);
                } catch (Throwable) {
                    Log::info('ZharPizzaParser - parseProduct - parseTopping error');
                }
                try {
                    $attribute = $this->parseJsonOptionsSize($item['json_options']);
                } catch (Throwable) {
                    Log::info('ZharPizzaParser - parseProduct - parseJsonOptionsSize error');
                }
                $this->products[] = new ProductDTO(
                    id: $item['uid'],
                    name: $item['title'],
                    image: $image,
                    imageMobile: $image,
                    topping: new ToppingDTO(
                        topping: $topping
                    ),
                    sizes: new SizeDTO($attribute),
                    flavors: new FlavorDTO(),
                    attribute: new ProductSizeDTO(
                        attribute: [
                            ['size_id' => $attribute[0]['id'] ?? '35-sm', 'flavor_id' => '', 'price' => (float)$item['price']]
                        ],
                    )
                );
            }
        } catch (Throwable) {
            Log::info('ZharPizzaParser - parseProduct error');
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
            Log::info('ZharPizzaParser - parseAttribute - error');
        }
        return new AttributeDTO(
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
     * Validation rulers
     * @return string[][]
     */
    protected function validationRules(): array
    {
        return [
            'uid' => ['required','string','max:50'],
            'title' => ['required', 'string'],
            'price' => ['required', 'string'],
            'descr' => ['required', 'string'],
            'gallery' => ['required', 'string'],
            'json_options' => ['nullable', 'string'],
        ];
    }
}
