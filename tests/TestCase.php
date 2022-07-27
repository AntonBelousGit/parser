<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Flavor;
use App\Models\Size;
use App\Models\Topping;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\FlavorDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductAttributeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Test data constructor
     *
     * @param string $id
     * @param Size $size
     * @param Flavor $flavor
     * @param Topping $topping
     * @param int $price
     * @return ParserProductDataDTO
     */
    public function getTestProductData(string $id, Size $size, Flavor $flavor, Topping $topping, int $price): ParserProductDataDTO
    {
        return
            new ParserProductDataDTO(
                products: collect(
                    [
                        new ProductDTO(
                            id: $id,
                            name: "Пiца 'PARSE'",
                            images: [
                    "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-2300x2300-70.jpg",
                ],
                            imagesMobile: [
                    "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-2300x2300-70.jpg",
                ],
                            toppings: collect([new ToppingDTO(id: (string)$topping->id, name: "Соус HELL")]),
                            sizes: collect([new SizeDTO(id: (string)$size->id, name: "BIG BOY")]),
                            flavors: collect([new FlavorDTO(id: (string)$flavor->id, name: "Holly")]),
                            attributes: new ProductAttributeDTO(
                                attributes: collect([['size_id' => $size->id, 'flavor_id' => $flavor->id, 'price' => $price]]),
                            ),
                        )
                    ]
                ),
                attributes: new AttributeDTO(
                    sizes: collect([new SizeDTO(id: (string)$size->id, name: 'BIG BOY')]),
                    flavors: collect([new FlavorDTO(id: (string)$flavor->id, name: 'Holly')]),
                    toppings: collect([new ToppingDTO(id: (string)$topping->id, name: 'Соус HELL')]),
                ),
            );
    }
}
