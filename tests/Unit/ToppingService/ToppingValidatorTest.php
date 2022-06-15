<?php

declare(strict_types=1);

namespace Tests\Unit\ToppingService;

use App\Services\ToppingService\Exception\InvalidToppingDataException;
use App\Services\ToppingService\ToppingValidator;
use Tests\TestCase;

class ToppingValidatorTest extends TestCase
{
    /**
     * @return void
     * @throws InvalidToppingDataException
     */
    public function testValidatorPassesCorrectData(): void
    {
        $topping = $this->getRateData();
        $toppingUnsanitized = array_merge($topping, ['price' => 1234]);
        $this->assertEquals(
            $this->getValidator()->validate($toppingUnsanitized, $this->getRateValidationRules()),
            $topping
        );
    }

    /**
     * @return array
     */
    protected function getRateData(): array
    {
        return [
            "id" => "57b9883e-7652-4590-9316-f45f2da2cad4",
            "name" => "Cоус Domino's",
        ];
    }

    /**
     * @return string[][]
     */
    protected function getRateValidationRules(): array
    {
        return [
            'id' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string'],
        ];
    }

    /**
     * @return ToppingValidator
     */
    protected function getValidator(): ToppingValidator
    {
        return app()->make(ToppingValidator::class);
    }
}
