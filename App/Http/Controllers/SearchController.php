<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(Request $request)
    {
        $entity = $request->query('entity', '');
        $query = $request->query('q', '');

        if (empty($entity) || empty($query)) {
            return response()->json(['error' => 'Query parameters "entity" and "q" are required'], 400);
        }

        return response()->json($this->searchService->search($entity, $query));
    }
}
