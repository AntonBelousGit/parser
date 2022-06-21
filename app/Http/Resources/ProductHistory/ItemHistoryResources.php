<?php

namespace App\Http\Resources\ProductHistory;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemHistoryResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'price' => $this->changed_value_from,
            'data' => $this->updated_at->format('d-m-y H:i:s')
        ];
    }
}
