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
        return [
            'id'=> $this->id,
            'curPrice'=> $this->price,
            'history' => (!$request->history)? ItemHistoryResources::collection($this->history) : null,
        ];
    }
}
