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
        $results = $index->search($query)->get();

        return $results->getResponse()->getResponse();
    }
    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return Good::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
    }
}
