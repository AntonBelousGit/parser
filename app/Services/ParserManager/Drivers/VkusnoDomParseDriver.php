<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductAttributeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use DiDom\Document;
use DiDom\Element;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class VkusnoDomParseDriver extends BaseDriver
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

        $parsedData = $productsParse->find('.row-catalog > .product-in-row > div > .product-wrapper');
        $collectTopping = collect();
        $products = collect();
        foreach ($parsedData as $product) {
            $data = $this->prepareParsedProducts($product);
            $product = $this->parseValidatorContract->validate($data, $this->validationRules());
            $topping = $this->parseTopping($product['topping']);
            $products->push(new ProductDTO(
                id: $product['url'],
                name: $product['name'],
                images: [$product['image']],
                imagesMobile: [$product['image']],
                toppings: $topping,
                sizes: collect(),
                flavors: collect(),
                attributes: new ProductAttributeDTO(
                    attributes: collect([['size_id' => 'standartna', 'flavor_id' => '','topping_id' => '', 'price' => (float)str_replace('грн', '', $product['price'])]]),
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
        $productContent = $product->first('.product-content');
        $name = $productContent->first('div > a')->text();
        $url = 'https://vkusno-dom.com'.$productContent->first('div > a')->attr('href');
        $topping = $productContent->first('.description > p')->text();
        $priceRaw = $productContent->find('.price > div')[0]->text();
        preg_match('/\d+/', $priceRaw, $price);
        $price = $price[0];
        $image = 'https://vkusno-dom.com'. $product->first('.img > a > img')->attr('src');

        return [
            'url' => $url,
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
            sizes: collect([new SizeDTO(id: 'standartna', name: 'Стандартна')]),
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
            'url' => ['required', 'string'],
            'name' => ['required', 'string', 'max:50'],
            'image' => ['required', 'string'],
            'image_mobile' => ['required', 'string'],
            'topping' => ['required', 'string'],
            'price' => ['required', 'string', 'max:10']
        ];
    }
}
