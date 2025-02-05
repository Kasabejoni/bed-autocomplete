<?php

namespace App\Services;

use App\Factories\SearchRepositoryFactory;
use App\Contracts\SearchRepositoryInterface;

class SearchService
{
    protected SearchRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = SearchRepositoryFactory::create();
    }

    public function search(string $entity, string $query): array
    {
        return $this->repository->search($entity, $query);
    }
}
