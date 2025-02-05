<?php

namespace App\Repositories;

use App\Contracts\SearchRepositoryInterface;

class MockRepository implements SearchRepositoryInterface
{
    private array $users = [
        [
            'id' => 1,
            'name' => 'John Doe',
            'description' => 'Software Engineer',
            'imageUrl' => 'https://example.com/john.jpg'
        ],
        [
            'id' => 2,
            'name' => 'Jane Smith',
            'description' => 'Data Scientist',
            'imageUrl' => 'https://example.com/jane.jpg'
        ],
        [
            'id' => 3,
            'name' => 'Alice Johnson',
            'description' => 'Product Manager',
            'imageUrl' => 'https://example.com/alice.jpg'
        ]
    ];

    public function search(string $entity, string $query): array
    {
        if ($entity !== 'users') {
            return [];
        }
        return array_values(array_filter($this->users, fn ($user) =>
            stripos($user['name'], $query) !== false || stripos($user['description'], $query) !== false
        ));
    }
}
