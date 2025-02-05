<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use OpenSearch\ClientBuilder;
use App\Contracts\SearchRepositoryInterface;

class OpenSearchRepository implements SearchRepositoryInterface
{
    protected $client;
    protected $index = 'users';

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([env('OPENSEARCH_HOST', 'http://opensearch:9200')])
            ->build();

        // Ensure the index exists and insert sample data if necessary
        $this->ensureIndexExists();
        $this->seedSampleData();
    }

    private function ensureIndexExists()
    {
        if (!$this->client->indices()->exists(['index' => $this->index])) {
            $this->client->indices()->create([
                'index' => $this->index,
                'body' => [
                    'settings' => [
                        'number_of_shards' => 1,
                        'number_of_replicas' => 1
                    ],
                    'mappings' => [
                        'properties' => [
                            'name' => ['type' => 'text'],
                            'description' => ['type' => 'text'],
                            'imageUrl' => ['type' => 'text']
                        ]
                    ]
                ]
            ]);
        }
    }

    private function seedSampleData()
    {
        $params = [
            'index' => $this->index,
            'size' => 1, // Check if at least one document exists
            'body' => [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ]
        ];

        $response = $this->client->search($params);

        // If there are no documents, insert sample data
        if ($response['hits']['total']['value'] == 0) {
            $sampleUsers = [
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
                ],
                [
                    'id' => 4,
                    'name' => 'Joni Kasabe',
                    'description' => 'Software Engineer',
                    'imageUrl' => 'https://example.com/joni.jpg'
                ],
            ];

            foreach ($sampleUsers as $user) {
                $this->client->index([
                    'index' => $this->index,
                    'id' => $user['id'],
                    'body' => $user
                ]);
            }
        }
    }

    public function search(string $entity, string $query): array
    {
        try {
            $params = [
                'index' => $this->index,
                'body'  => [
                    'query' => [
                        'bool' => [
                            'should' => [
                                // Fuzzy match for typos
                                ['match' => ['name' => ['query' => $query, 'fuzziness' => 'AUTO']]],
                                ['match' => ['description' => ['query' => $query, 'fuzziness' => 'AUTO']]],

                                // Wildcard for partial matches
                                ['wildcard' => ['name' => ['value' => "*$query*", 'case_insensitive' => true]]],
                                ['wildcard' => ['description' => ['value' => "*$query*", 'case_insensitive' => true]]]
                            ],
                            'minimum_should_match' => 1
                        ]
                    ]
                ]
            ];

            $results = $this->client->search($params);
            return array_map(fn ($hit) => [
                'name' => $hit['_source']['name'],
                'description' => $hit['_source']['description'] ?? '',
                'imageUrl' => $hit['_source']['imageUrl'] ?? ''
            ], $results['hits']['hits']);

        } catch (\Exception $e) {
            Log::error("Search failed: " . $e->getMessage());
            return [];
        }
    }


}
