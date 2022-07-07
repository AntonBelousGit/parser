<?php

declare(strict_types=1);

namespace App\Services\StoreManager\Drivers\AttributeDriver;

use App\Services\ParserManager\DTOs\AttributeDTO;
use App\Services\StoreManager\Contracts\AttributeServiceContract;
use Illuminate\Support\Facades\Log;
use Throwable;

class AttributeDriver implements AttributeServiceContract
{
    /**
     * @param AttributeValidator $attributeValidator
     */
    public function __construct(
        protected AttributeValidator $attributeValidator,
    ) {
    }

    /**
     * @param AttributeDTO $attribute
     * @param array $config
     * @return void
     */
    public function updateOrCreate(AttributeDTO $attribute, array $config): void
    {
        try {
            foreach ($config as $attributeKey => $attributeModel) {
                $this->attribute($attribute, $attributeKey, $attributeModel);
            }
        } catch (Throwable $exception) {
            Log::info('Store or update AttributeService - problem', ['data' => $exception]);
        }
    }

    /**
     * Save or Update attribute to DB
     * @param AttributeDTO $attribute
     * @param string $attributeKey
     * @param string $attributeModel
     */
    protected function attribute(AttributeDTO $attribute, string $attributeKey, string $attributeModel):void
    {
        foreach ($attribute->$attributeKey as $item) {
            try {
                $item = $this->attributeValidator->validate($item);
                $data = [
                    'id' => $item['id'],
                    'name' => html_entity_decode($item['name']),
                ];
                $updateModel = ($attributeModel)::find($data['id']);
                if ($updateModel) {
                    $updateModel->update($data);
                } else {
                    ($attributeModel)::create($data);
                }
            } catch (Throwable) {
                Log::info('FlavorService error create/update');
            }
        }
    }
}
