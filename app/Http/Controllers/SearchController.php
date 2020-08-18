<?php


namespace App\Http\Controllers;


use App\Models\Good;
use App\Repositories\ElasticsearchRepository;
use App\Repositories\GoodsRepository;
use Illuminate\Http\Request;

class SearchController
{
    public function search(Request $request)
    {
        $q = $request->get('q', null);

//        $repository = app()->make(GoodsRepository::class);
        $repository = app()->make(ElasticsearchRepository::class);

        if ($q) {
            $goods = $repository->search(request('q'));
        } else {
            $goods = Good::limit(50)->get();
        }

        return view('welcome', ['goods' => $goods]);
    }
}
