<?php

namespace Tests;

use App\Models\Flavor;
use App\Models\Size;
use App\Models\Topping;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\ParserManager\DTOs\FlavorDTO;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\DTOs\ProductDTO;
use App\Services\ParserManager\DTOs\ProductSizeDTO;
use App\Services\ParserManager\DTOs\SizeDTO;
use App\Services\ParserManager\DTOs\ToppingDTO;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;

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
     * @return ParserProductDataDTO[]
     */
    public function getTestProductData(string $id, Size $size, Flavor $flavor, Topping $topping, int $price): array
    {
        return [
            new ParserProductDataDTO(
                products: [
                new ProductDTO(
                    id: $id,
                    name: "Пiца 'PARSE'",
                    image: [
                    2300 => "https://media.dominos.ua/__sized__/menu/product_osg_image/2021/07/27/PizzaMNHTTNingFULL-min-thumbnail-2300x2300-70.jpg",
                ],
                    imageMobile: [
                    "https://media.dominos.ua/__sized__/menu/product_osg_image_mobile/2021/07/27/Manhatten_slice_collageweb-min-thumbnail-2300x2300-70.jpg",

                ],
                    topping: new ToppingDTO(
                        topping: [
                        [
                            "id" => $topping->id,
                            "name" => "Соус HELL",
                        ],
                    ]
                    ),
                    sizes: new SizeDTO(
                        size: [
                        [
                            "id" => $size->id,
                            "name" => "BIG BOY"
                        ],
                    ]
                    ),
                    flavors: new FlavorDTO(
                        flavor: [
                        [
                            "id" => $flavor->id,
                            "name" => "Holly",
                        ],
                    ]
                    ),
                    attribute: new ProductSizeDTO(
                        attribute: [
                            [
                                "size_id" => $size->id,
                                "flavor_id" => $flavor->id,
                                "price" => $price
                            ],
                        ]
                    )
                )
            ],
                attributes: new AttributeDTO(
                    size: [
                [
                    "id" => $size->id,
                    "name" => "BIG BOY"
                ],
            ],
                    flavor: [
                [
                    "id" => $flavor->id,
                    "name" => "Holly",
                ],
            ],
                    topping: [
                    [
                        "id" => $topping->id,
                        "name" => "Соус HELL",
                    ],
                ]
                ),
            )
        ];
    }

    /**
     * Check equals Model before update dnd after update
     *
     * @param $model
     * @param $checkArray
     * @param string[] $ignore
     */
    public function checkTwoModelAssertNotEquals($model, $checkArray, array $ignore = ['created_at','updated_at'])
    {
        $modelToArray = $model->toArray();
        $model = Arr::except($modelToArray, $ignore);
        foreach ($model as $key => $item) {
            if ($key === 'id') {
                $this->assertEquals($model['id'], $checkArray['id']);
                continue;
            }
            $this->assertNotEquals($item, $checkArray[$key]);
        }
    }
}
