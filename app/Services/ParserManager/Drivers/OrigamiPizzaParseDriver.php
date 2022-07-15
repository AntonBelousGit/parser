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
use DiDom\Document;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class OrigamiPizzaParseDriver implements ParseDriverContract, ParseManagerAttributeDriver
{
    /**
     * All products
     *
     * @var array
     */
    protected array $products = [];

    /**
     * OrigamiPizzaParseDriver constructor.
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
     * @return Document
     */
    public function callConnectToParse(string $url): Document
    {
        return new Document($url, true);
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
            $products = $productsParse->find('.productblock');
            foreach ($products as $item) {
                $name = $item->find('.product-info > h3')[0]->text();
                $id = Str::slug($name);
                $topping = $item->find('.product-info > .product-text > p')[0]->text();
                $price = $item->find('.product-info > p')[0]->text();
                $image = "https://origami.od.ua/" . $item->find('.productitem > img')[0]->attr('src');
                $data =  [
                    'id'  => $id,
                    'name' => $name,
                    'image' => $image,
                    'image_mobile' => $image,
                    'topping' => $topping,
                    'price' => $price
                ];

                $item = $this->parseValidatorContract->validate($data, $this->validationRules());
                $topping = [];
                try {
                    $topping = $this->parseTopping($item['topping']);
                } catch (Throwable) {
                    Log::info('Origami Pizza  - parseProduct - parseTopping error');
                }
                $this->products[] = new ProductDTO(
                    id: $item['id'],
                    name: $item['name'],
                    image: [$item['image']],
                    imageMobile: [$item['image']],
                    topping:$topping,
                    sizes: collect(),
                    flavors: collect(),
                    attribute: new ProductSizeDTO(
                        attribute: collect([['size_id' => 'standard', 'flavor_id' => '', 'price' => (float)str_replace('грн', '', $item['price'])]]),
                    )
                );
            }
        } catch (Throwable) {
            Log::info('Origami Pizza - parseProduct error');
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
        $tempCollectTopping = collect();
        $attrTopping = collect();
        try {
            foreach ($array as $item) {
                $tempCollectTopping->push($item->topping);
            }
            $attrTopping->push(collectionUniqueKey($tempCollectTopping->flatten(1), 'id'));
        } catch (Throwable) {
            Log::info('Origami Pizza - parseAttribute - error');
        }

        return new AttributeDTO(
            size: collect([new SizeDTO(id:'standard', name: 'Standard')]),
            flavor: collect(),
            topping: $attrTopping
        );
    }

    /**
     * Parse attribute topping
     *
     * @param $data
     * @return Collection
     */
    protected function parseTopping($data): Collection
    {
        $tempCollect = collect();
        $array = array_map('trim', explode(',', $data));
        foreach ($array as $item) {
            $cleanValueHtml = trim(strip_tags($item));
            $tempCollect->push(new ToppingDTO(id:Str::slug($cleanValueHtml), name:$cleanValueHtml));
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
            'id' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:200'],
            'image' => ['required', 'string'],
            'image_mobile' => ['required', 'string'],
            'topping' => ['required', 'string'],
            'price' => ['required', 'string']
        ];
    }
}
