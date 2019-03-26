<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'price'       => $this->price,
            'list_price'  => $this->list_price,
            'price_sold'  => $this->price_sold,
            'qty'         => $this->qty,
            'description' => $this->description,
            'dimension'   => $this->dimension,
            'available'   => $this->available,
            'added'       => $this->created_at->format("m/d/Y"), //@temp we'll switch this to using js moment on the frontend
            'photo'       => $this->getFirstMediaUrl('', 'thumb'),
        ];
    }
}
