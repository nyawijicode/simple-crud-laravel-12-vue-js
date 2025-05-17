<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = $this->resource->toArray();
        
        return [
            'data' => $this->collection,
            'meta' => [
                'current_page' => $resource['current_page'] ?? 1,
                'from' => $resource['from'] ?? null,
                'last_page' => $resource['last_page'] ?? 1,
                'per_page' => (int)($resource['per_page'] ?? 10),
                'to' => $resource['to'] ?? null,
                'total' => $resource['total'] ?? $this->collection->count(),
            ],
        ];
    }
}