<?php


namespace App\Repositories;

use Illuminate\Support\Collection;

interface SearchRepository
{
    public function search(string $query = ''): Collection;
}
