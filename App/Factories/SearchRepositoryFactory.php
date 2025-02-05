<?php

namespace App\Factories;

use App\Contracts\SearchRepositoryInterface;
use App\Repositories\MariaDBSearchRepository;
use App\Repositories\MockRepository;
use App\Repositories\OpenSearchRepository;

class SearchRepositoryFactory
{
    public static function create(): SearchRepositoryInterface
    {
        $provider = env('SEARCH_PROVIDER', 'open_search');
        return match ($provider) {
            'open_search' => new OpenSearchRepository(),
            'mariadb' => new MariaDBSearchRepository(),
            default => new MockRepository()
        };
    }
}
