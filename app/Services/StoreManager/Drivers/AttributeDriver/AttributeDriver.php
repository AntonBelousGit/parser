<?php
declare(strict_types=1);

namespace App\Services\StoreManager\Drivers\AttributeDriver;

use App\Models\Flavor;
use App\Models\Size;
use App\Models\Topping;
use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\StoreManager\Contracts\AttributeDriverContract;
use Illuminate\Support\Facades\Log;
use Throwable;

class AttributeDriver implements AttributeDriverContract
{
    /**
     * Attribute model
     */
    const ATTRIBUTEMODEL = [
        'size' => Size::class,
        'flavor' => Flavor::class,
        'topping' => Topping::class
    ];
    /**
     * @param AttributeValidator $attributeValidator
     */
    public function __construct(
        protected AttributeValidator $attributeValidator,
    ) {
    }

    /**
     * @param AttributeDTO $attribute
     * @return void
     */
    public function updateOrCreate(AttributeDTO $attribute): void
    {
        try {
            foreach ($attribute as $attributeKey => $attributeData) {
                $this->attribute($attributeKey, $attributeData);
            }
        } catch (Throwable $exception) {
            Log::info('Store or update AttributeService - problem', ['data' => $exception]);
        }
    }

    /**
     * Save or Update attribute to DB
     * @param string $attributeKey
     * @param array $attributeData
     */
    protected function attribute(string $attributeKey, array $attributeData):void
    {
        foreach ($attributeData as $item) {
            try {
                $item = $this->attributeValidator->validate($item);
                $data = [
                    'id' => $item['id'],
                    'name' => html_entity_decode($item['name']),
                ];
                $updateModel = (self::ATTRIBUTEMODEL[$attributeKey])::find($data['id']);
                if ($updateModel) {
                    $updateModel->update($data);
                } else {
                    (self::ATTRIBUTEMODEL[$attributeKey])::create($data);
                }
            } catch (Throwable) {
                Log::info($attributeKey.' error create/update');
            }
        }
    }
}
