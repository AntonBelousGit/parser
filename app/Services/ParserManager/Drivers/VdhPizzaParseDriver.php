<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\Contracts\ParseDriverContract;
use App\Services\ParserManager\Contracts\ParseServiceAttributeDriver;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\FlavorDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use App\Services\ParserManager\Drivers\ParseVdhPizza\ParserService\Contracts\VdhPizzaProductValidatorContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class VdhPizzaParseDriver implements ParseDriverContract, ParseServiceAttributeDriver
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
                    Log::info('VdhPizzaParser - parseProduct - parseTopping error');
                }
                $this->products[] = new ProductDTO(
                    id: $item['uid'],
                    name: $item['title'],
                    image: [$image[0]->img],
                    imageMobile: [$image[0]->img],
                    topping: new ToppingDTO(
                        topping: $topping
                    ),
                    sizes: new SizeDTO(['id'=> 'standard',"name" => "Standard"]),
                    flavors: new FlavorDTO(),
                    attribute: new ProductSizeDTO(
                        attribute: [
                            ['size_id'=> 'standard', 'flavor_id' => '','price'=> (float)$item['price']]
                        ],
                    )
                );
            }
        } catch (Throwable) {
            Log::info('VdhPizzaParser - parseProduct error');
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
        $tempArrTopping = [];
        $attrTopping = [];
        try {
            foreach ($array as $item) {
                $tempArrTopping[] = $item->topping->topping;
            }
            $attrTopping = arrayUniqueKey(call_user_func_array('array_merge', $tempArrTopping), 'id');
        } catch (Throwable) {
            Log::info('VdhPizzaParser - parseAttribute - size error');
        }

        return new AttributeDTO(
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
}
