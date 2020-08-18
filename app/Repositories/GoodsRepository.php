<?php


namespace App\Repositories;


use App\Models\Good;
use Illuminate\Support\Collection;

class GoodsRepository implements SearchRepository
{
    public function search(string $query = ''): Collection
    {
        return Good::query()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();
    }
}
