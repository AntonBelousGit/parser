<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'image' => $this->image ?? '',
            'image_mobile' => $this->image_mobile ?? '',
            'variants' => ProductHistoryResources::collection($this->attributeProduct ?? ''),
        ];
    }
}
