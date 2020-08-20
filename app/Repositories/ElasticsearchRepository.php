<?php


namespace App\Repositories;

use App\Models\Good;
use Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class ElasticsearchRepository implements SearchRepository
{
    /** @var \Elasticsearch\Client */
    private $elasticsearch;
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }
    public function search(string $query = ''): Collection
    {
        $items = $this->searchOnElasticsearch($query);
        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query = ''): array
    {
        $model = new Good();
        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['name^5', 'description', 'categories'],
                        'query' => $query,
                    ],
                ],
                'size' => 50
            ],
        ]);
        return $items;
    }
    private function buildCollection(array $items): Collection
    {
        $response = new Collection;
        foreach ($items['hits']['hits'] as $hit) {
            $good = new Good();
            $good->name = $hit['_source']['name'];
            $good->description = $hit['_source']['description'];
            $good->categories = $hit['_source']['categories'];
            $good->quantity = $hit['_source']['quantity'];
            $response->push($good);
        }

        return $response;
    }
}
