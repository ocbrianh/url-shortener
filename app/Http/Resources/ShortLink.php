<?php

namespace App\Http\Resources;
use Illuminate\Support\URL;

use Illuminate\Http\Resources\Json\JsonResource;

class ShortLink extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'shortlink' => url("/{$this->code}"),
            'code' => $this->code,
            'link' => $this->link,
        ];
    }
}
