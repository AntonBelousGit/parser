<?php

declare(strict_types=1);

namespace ParserManagerTests;

use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\ParseManager;
use Mockery;
use Tests\TestCase;

class ParseManagerTest extends TestCase
{
    public function testConnectionToParsedPage()
    {
        $this->getParse();
        $this->assertTrue(true);
    }

    public function testGetProductDataFromParsedPage()
    {
        $response = $this->getParse();
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response[0]->products);
    }

    public function testGetAttributeDataFromParsedPage()
    {
        $response = $this->getParse()->callParse(config('parsers'));
        if (count($response[0]->attributes->size) > 0 && count($response[0]->attributes->topping) > 0 && count($response[0]->attributes->flavor)) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * @return ParseManager
     */
    protected function getParse(): ParseManager
    {
        $parseManagerMock = Mockery::mock('ParseManager');
        $parseManagerMock->shouldReceive('callParse')->with(config('parsers'));
        dd($parseManagerMock);
        return $parseManagerMock;
    }

    /**
     * @param array $carriers
     * @return ParserProductDataDTO
     */
    protected function getTestRates(array $carriers): ParserProductDataDTO
    {
        $rates = [];
        foreach ($carriers as $carrier) {
            $rates[] = new Rate(
                carrier: $carrier,
                originPortIsoCode: 'ESBCN',
                destinationPortIsoCode: 'USMIA',
                pricePerContainer: '100',
                pricePerShipment: '0',
                currency: 'USD',
                expiresAt: Carbon::now()->addHour()
            );
        }

        return new ParserProductDataDTO(
            products: $data,
            attributes: $attribute,
        );
    }
}
