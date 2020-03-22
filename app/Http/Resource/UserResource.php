<?php

namespace App\Http\Resource\Producer;


use App\Http\Resource\JsonApiResource;

class UserResource extends JsonApiResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'email' => $this->email,
        ];
    }
}
