<?php

namespace App\Http\Resource\Producer;


use App\Http\Resource\JsonApiResource;

class RiskGroupResource extends JsonApiResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
        ];
    }
}
