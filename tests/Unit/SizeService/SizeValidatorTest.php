<?php

declare(strict_types=1);

namespace Tests\Unit\SizeService;

use App\Services\SizeService\SizeValidator;
use Tests\TestCase;
use Throwable;

class SizeValidatorTest extends TestCase
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
            "id" => "e1e74edf-1431-4a90-8234-5039265d7ae6",
            "name" => "Стандартна",

        ];
    }

    /**
     * @return string[][]
     */
    protected function getRateValidationRules(): array
    {
        return [
            'id' => ['required','string','max:50'],
            'name' => ['required', 'string'],
        ];
    }

    /**
     * @return SizeValidator
     */
    protected function getValidator(): SizeValidator
    {
        return app()->make(SizeValidator::class);
    }
}
