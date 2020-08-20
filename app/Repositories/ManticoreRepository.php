<?php


namespace App\Repositories;

use App\Models\Good;
use Manticoresearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class ManticoreRepository implements SearchRepository
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    public function search(string $query = ''): Collection
    {
        $items = $this->searchManticore($query);

        return $this->buildCollection($items);
    }

    private function searchManticore(string $query = '') : array
    {
        $index = $this->client->index('goods');
        $results = $index->search($query)->limit(50)->get();

        return $results->getResponse()->getResponse();
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
