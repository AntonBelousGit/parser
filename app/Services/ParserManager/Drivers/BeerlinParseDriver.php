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

class BeerlinParseDriver extends BaseDriver
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
        $parsedData = $productsParse->find('.woocommerce-loop-product__title > a');
        $collectTopping = collect();
        $products = collect();
        foreach ($parsedData as $product) {
            $url = $product->attr('href');
            $xml = new Document($this->getHtml($url));
            $data = $this->prepareParsedProducts($xml->first('.nm-single-product'));
            $data['url'] = $url;
            $topping = $this->parseTopping($data['topping']);
            $product = $this->parseValidatorContract->validate($data, $this->validationRules());
            $products->push(new ProductDTO(
                id: $product['url'],
                name: $product['name'],
                images: [$product['image']],
                imagesMobile: [$product['image']],
                toppings: $topping,
                sizes: collect([new SizeDTO(id: '30sm', name: "30см")]),
                flavors: collect(),
                attributes: new ProductAttributeDTO(
                    attributes: collect([['size_id' => '30sm', 'flavor_id' => '', 'topping_id' => '', 'price' => (float)str_replace('грн', '', $product['price'])]]),
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
        $image = $product->first('.woocommerce-product-gallery__image > a')->attr('href');
        $imageMobile = $product->first('.woocommerce-product-gallery__image > a > img')->attr('src');
        $productContent = $product->first('.summary');
        $name = $productContent->first('h1')->text();
        $toppingTemp1 = $productContent->find('.woocommerce-product-details__short-description > p');
        $toppingTemp2 = $product->find('.nm-tabs-panel-inner');
        $topping = [];
        if (!empty($toppingTemp1)) $topping = $toppingTemp1;
        if (!empty($toppingTemp2)) $topping = $toppingTemp2;
        $priceRaw = $productContent->first('.woocommerce-Price-amount')->text();
        preg_match('/\d+/', $priceRaw, $price);
        $price = $price[0];

        return [
            'url' => '',
            'name' => $name,
            'image' => $image,
            'image_mobile' => $imageMobile,
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
            sizes: collect([new SizeDTO(id: '30sm', name: '30cм')]),
            flavors: collect(),
            toppings: $this->removeDuplicates($topping->flatten(1), 'id')
        );
    }

    /**
     * Parse attribute topping
     *
     * @param array $data
     * @return Collection
     */
    protected function parseTopping(array $data): Collection
    {
        $tempCollect = collect();
        if ($data) {
            array_pop($data);
            if (count($data) === 1) {
                $data = preg_replace('/Состав- /', '', $data[0]->text());
                $array = array_map('trim', explode(',', $data));
                foreach ($array as $topping) {
                    $tempCollect->push(new ToppingDTO(id: Str::slug($topping), name: Str::ucfirst($topping)));
                }
            } else {
                foreach ($data as $item) {
                    $data = preg_replace('/-/', '', $item->text());
                    $tempCollect->push(new ToppingDTO(id: Str::slug($data), name: Str::ucfirst($data)));
                }
            }
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
            'topping' => ['nullable'],
            'price' => ['required', 'string', 'max:10']
        ];
    }
}
