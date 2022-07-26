<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use DiDom\Document;
use DiDom\Element;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrigamiPizzaParseDriver extends BaseDriver
{
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
     *Parse get data - return prepare data
     *
     * @param string $url
     * @return ParserProductDataDTO
     * @throws InvalidSelectorException
     */
    public function parseProduct(string $url): ParserProductDataDTO
    {
        $productsParse = new Document($this->getHtml($url));
        $parsedData = $productsParse->find('.productblock');
        $collectTopping = collect();
        $products = collect();
        foreach ($parsedData as $product) {
            $data = $this->prepareParsedProducts($product);
            $product = $this->parseValidatorContract->validate($data, $this->validationRules());
            $topping = $this->parseTopping($product['topping']);
            $products->push(new ProductDTO(
                id: $product['id'],
                name: $product['name'],
                images: [$product['image']],
                imagesMobile: [$product['image']],
                toppings: $topping,
                sizes: collect(),
                flavors: collect(),
                attributes: new ProductSizeDTO(
                    attributes: collect([['size_id' => 'standard', 'flavor_id' => '', 'price' => (float)str_replace('грн', '', $product['price'])]]),
                )
            ));
            $collectTopping->push($topping);
        }
        $parseAttribute = $this->parseAttribute($collectTopping);

        return new ParserProductDataDTO(
            products: $products,
            attributes: $parseAttribute,
        );
    }

    /**
     * Prepare product before parse
     *
     * @param Element $product
     * @return array
     * @throws InvalidSelectorException
     */
    protected function prepareParsedProducts(Element $product): array
    {
        $name = $product->find('.product-info > h3')[0]->text();
        $id = Str::slug($name);
        $topping = $product->find('.product-info > .product-text > p')[0]->text();
        $price = $product->find('.product-info > p')[0]->text();
        $image = "https://origami.od.ua/" . $product->find('.productitem > img')[0]->attr('src');

        return [
            'id' => $id,
            'name' => $name,
            'image' => $image,
            'image_mobile' => $image,
            'topping' => $topping,
            'price' => $price
        ];
    }

    /**
     * Prepare parsed attribute data
     *
     * @param Collection $topping
     * @return AttributeDTO
     */
    protected function parseAttribute(Collection $topping): AttributeDTO
    {
        return new AttributeDTO(
            sizes: collect([new SizeDTO(id: 'standard', name: 'Standard')]),
            flavors: collect(),
            toppings: $this->removeDuplicates($topping->flatten(1), 'id')
        );
    }

    /**
     * Parse attribute topping
     *
     * @param string $data
     * @return Collection
     */
    protected function parseTopping(string $data): Collection
    {
        $tempCollect = collect();
        $array = array_map('trim', explode(',', $data));
        foreach ($array as $topping) {
            $cleanValueHtml = trim(strip_tags($topping));
            $tempCollect->push(new ToppingDTO(id: Str::slug($cleanValueHtml), name: Str::ucfirst($cleanValueHtml)));
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
