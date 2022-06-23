<?php

namespace App\Http\Resources\ProductHistory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductHistoryResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array(
            'size_id' => $this->size_id ?? '',
            'flavor_id' => $this->flavor_id ?? '',
            'variantCurrentPrice'=> $this->price ?? '',
            'variantPriceHistory' => (!$request->history)? ItemHistoryResources::collection($this->history) : null,
        );
    }
}
