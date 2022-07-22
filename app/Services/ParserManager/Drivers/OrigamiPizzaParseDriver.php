<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use App\Services\ParserManager\Contracts\ParseDriverContract;
use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrigamiPizzaParseDriver implements ParseDriverContract
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
     * @param ConnectToParseServiceContract $parseServiceContract
     */
    public function __construct(
        protected ParseValidatorContract $parseValidatorContract,
        protected ConnectToParseServiceContract $parseServiceContract,
    ) {
    }

    /**
     *Parse get data - return prepare data
     *
     * @param string $url
     * @return ParserProductDataDTO
     * @throws InvalidSelectorException
     */
    public function parseProduct(string $url): ParserProductDataDTO
    {
        $productsParse = new Document($this->parseServiceContract->connect($url));
        $products = $productsParse->find('.productblock');
        $collectTopping = collect();
        foreach ($products as $item) {
            $name = $item->find('.product-info > h3')[0]->text();
            $id = Str::slug($name);
            $topping = $item->find('.product-info > .product-text > p')[0]->text();
            $price = $item->find('.product-info > p')[0]->text();
            $image = "https://origami.od.ua/" . $item->find('.productitem > img')[0]->attr('src');
            $data = [
                'id' => $id,
                'name' => $name,
                'image' => $image,
                'image_mobile' => $image,
                'topping' => $topping,
                'price' => $price
            ];

            $item = $this->parseValidatorContract->validate($data, $this->validationRules());
            $topping = $this->parseTopping($item['topping']);
            $this->products[] = new ProductDTO(
                id: $item['id'],
                name: $item['name'],
                image: [$item['image']],
                imageMobile: [$item['image']],
                topping: $topping,
                sizes: collect(),
                flavors: collect(),
                attribute: new ProductSizeDTO(
                    attribute: collect([['size_id' => 'standard', 'flavor_id' => '', 'price' => (float)str_replace('грн', '', $item['price'])]]),
                )
            );
            $collectTopping->push($topping);
        }
        $parseAttribute = $this->parseAttribute($collectTopping);
        return new ParserProductDataDTO(
            products: $this->products,
            attributes: $parseAttribute,
        );
    }

    /**
     * Prepare parsed attribute data
     *
     * @param Collection $topping
     * @return AttributeDTO
     */
    protected function parseAttribute(Collection $topping): AttributeDTO
    {
        $attrTopping = collect();
        $attrTopping->push(collectionUniqueKey($topping->flatten(1), 'id'));
        return new AttributeDTO(
            size: collect([new SizeDTO(id: 'standard', name: 'Standard')]),
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
            $tempCollect->push(new ToppingDTO(id: Str::slug($cleanValueHtml), name: $cleanValueHtml));
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
