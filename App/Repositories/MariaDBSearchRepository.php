<?php

namespace App\Repositories;

use App\Contracts\SearchRepositoryInterface;
use Illuminate\Support\Facades\DB;

class MariaDBSearchRepository implements SearchRepositoryInterface
{
    protected array $tableMap = [
        'users' => 'users',
        'products' => 'products',
        'posts' => 'posts'
    ];

    public function search(string $entity, string $query): array
    {
        if (!isset($this->tableMap[$entity])) {
            return [];
        }

        $results = DB::table($this->tableMap[$entity])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%");
            })
            ->select('id', 'name', 'description', 'imageUrl')
            ->get()
            ->toArray();

        return array_map(fn ($result) => [
            'id' => $result->id,
            'name' => $result->name,
            'description' => $result->description ?? '',
            'imageUrl' => $result->imageUrl ?? ''
        ], $results);
    }
}
