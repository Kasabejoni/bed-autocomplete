<?php

namespace App\Contracts;

interface SearchRepositoryInterface
{
    public function search(string $entity, string $query): array;
}