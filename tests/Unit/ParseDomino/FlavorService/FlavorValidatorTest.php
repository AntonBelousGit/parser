<?php

declare(strict_types=1);

namespace Tests\Unit\ParseDomino\FlavorService;

use App\Services\ParseDomino\FlavorService\FlavorValidator;
use Tests\TestCase;
use Throwable;

class FlavorValidatorTest extends TestCase
{
    /**
     * @return void
     * @throws Throwable
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
            "id" => "8cce4b72-3386-415c-b983-70711ea235e7",
            "name" => "Стандартне",
            "code" => "USA"
        ];
    }

    /**
     * @return string[][]
     */
    protected function getRateValidationRules(): array
    {
        return [
            'id' => ['required','string','max:50'],
            'name' => ['required', 'string','max:100'],
            'code' => ['required', 'string'],
        ];
    }

    /**
     * @return FlavorValidator
     */
    protected function getValidator(): FlavorValidator
    {
        return app()->make(FlavorValidator::class);
    }
}
