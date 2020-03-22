<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Trait JsonApiResponseTrait
 * @package Nice\Wallets
 */
trait JsonApiResponseTrait
{
    /**
     * Absolutely simple top-level-only response format for the JsonApi specification.
     * @see http://jsonapi.org/format/#document-top-level
     *
     * @param $data
     * @param array $meta
     * @param array $errors
     * @param int $returnCode
     * @param array $headers
     * @return JsonResponse
     */
    protected function toJsonApiResponse(
        $data,
        array $meta = [],
        array $errors = [],
        int $returnCode = 200,
        array $headers = []
    ): JsonResponse
    {
        return new JsonResponse(
            [
                'data' => $data ?? [],
                'meta' => $meta,
                'errors' => $errors
            ],
            $returnCode,
            $headers
        );
    }
}
