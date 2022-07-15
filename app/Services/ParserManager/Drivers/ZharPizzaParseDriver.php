<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\Contracts\ParseDriverContract;
use App\Services\ParserManager\Contracts\ParseManagerAttributeDriver;
use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ZharPizzaParseDriver implements ParseDriverContract, ParseManagerAttributeDriver
{
    /**
     * All products
     *
     * @var array
     */
    protected array $products = [];

    /**
     * ZharPizzaParseService constructor.
     *
     * @param ParseValidatorContract $parseValidatorContract
     */
    public function __construct(
        protected ParseValidatorContract $parseValidatorContract,
    ) {
    }

    /**
     * Connect to parsed url
     *
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
     *
     * @param string $url
     * @return array
     */
    public function parseProduct(string $url): array
    {
        try {
            $productsParse = $this->callConnectToParse($url);
            foreach ($productsParse->products as $item) {
                $item = $this->parseValidatorContract->validate(collect($item)->toArray(), $this->validationRules());
                $topping = collect();
                $attribute = collect();
                $image = (json_decode($item['gallery']));
                try {
                    $topping = $this->parseJsonTopping($item['descr']);
                } catch (Throwable) {
                    Log::info('ZharPizzaParser - parseProduct - parseTopping error');
                }
                try {
                    $attribute = $this->parseJsonSize($item['json_options']);
                } catch (Throwable) {
                    Log::info('ZharPizzaParser - parseProduct - parseJsonOptionsSize error');
                }
                $this->products[] = new ProductDTO(
                    id: $item['uid'],
                    name: $item['title'],
                    image: $image,
                    imageMobile: $image,
                    topping: $topping,
                    sizes: $attribute,
                    flavors: collect(),
                    attribute: new ProductSizeDTO(
                        attribute: collect([['size_id' => $attribute[0]->id ?? '35-sm', 'flavor_id' => '', 'price' => (float)$item['price']]]),
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
     *
     * @param array $array
     * @return AttributeDTO
     */
    public function parseAttribute(array $array = []): AttributeDTO
    {
        $tempCollectSize = collect();
        $tempCollectTopping = collect();
        $attrSize = collect();
        $attrTopping = collect();

        try {
            foreach ($array as $item) {
                $tempCollectSize->push($item->sizes);
                $tempCollectTopping->push($item->topping);
            }
            $attrSize->push(collectionUniqueKey($tempCollectSize->flatten(1), 'id'));
            $attrTopping->push(collectionUniqueKey($tempCollectTopping->flatten(1), 'id'));
        } catch (Throwable) {
            Log::info('ZharPizzaParser - parseAttribute - error');
        }
        return new AttributeDTO(
            size: $attrSize,
            flavor: collect(),
            topping: $attrTopping
        );
    }

    /**
     * Parse attribute topping from json
     *
     * @param $data
     * @return Collection
     */
    protected function parseJsonTopping($data): Collection
    {
        $tempCollect = collect();
        $array = array_map('trim', explode(',', $data));
        foreach ($array as $item) {
            $tempCollect->push(new ToppingDTO(id:Str::slug($item), name:$item));
        }
        return $tempCollect;
    }

    /**
     *Parse attribute size from json
     *
     * @param $data
     * @return Collection
     */
    protected function parseJsonSize($data): Collection
    {
        $data = json_decode($data);
        $tempCollect = collect();
        foreach ($data[0]->values as $item) {
            $tempCollect->push(new SizeDTO(id:Str::slug($item), name:$item));
        }
        return $tempCollect;
    }

    /**
     * Validation rulers
     *
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
