<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait PaginatedResponse
{

    private function getOnlyKeyValuePairs(array $args): array
    {
        $argsData = [];
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $argsData = array_merge($argsData, $arg);
            }
        }
        return $argsData;
    }

    public function paginatedResponse(ResourceCollection $resourceCollection, ...$args): array
    {
        $resource = $resourceCollection->resource;
        $key_value_pairs = $this->getOnlyKeyValuePairs($args);

        return  [
            ...$key_value_pairs,
            'data' => $resourceCollection,
            'meta' => [
                'current_page' => $resource->currentPage(),
                'last_page' => $resource->lastPage(),
                'per_page' => $resource->perPage(),
                'total' => $resource->total(),
            ],
        ];
    }

}
