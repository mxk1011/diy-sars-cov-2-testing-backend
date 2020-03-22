<?php

namespace App\Http\Resource;

class TestResource extends JsonApiResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'test_number' => $this->test_number,
            'result' => $this->result,
        ];
    }
}
