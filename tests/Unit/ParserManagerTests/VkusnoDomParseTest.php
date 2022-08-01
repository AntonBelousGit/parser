<?php

declare(strict_types=1);

namespace ParserManagerTests;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ParseTestCase;
use Throwable;

class VkusnoDomParseTest extends ParseTestCase
{
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException
     */
    public function testOrigamiParse()
    {
        $config = config('parsers.vkusnoDom');
        $response = $this->parse($config, 'vkusnoDom', 'DiDom');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->sizes[0]);
        $this->assertNotNull($response->attributes->toppings[0]);
    }

    public function testOrigamiValidationProblemParse()
    {
        $config = config('parsers.vkusnoDom');
        try {
            $this->parse($config, 'corruptFile/vkusnoDomValidation', 'DiDom');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Parse data is invalid', $message);
        }
    }

    /**
     * Change tag p to span
     */
    public function testOrigamiCorruptedFileParse()
    {
        $config = config('parsers.vkusnoDom');
        try {
            $this->parse($config, 'corruptFile/vkusnoDomCorruptedParse', 'DiDom');
        } catch (Throwable $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Call to a member function text() on null', $message);
        }
    }
}
