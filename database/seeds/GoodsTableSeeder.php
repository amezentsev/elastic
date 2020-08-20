<?php


use App\Models\Good;
use Illuminate\Database\Seeder;

class GoodsTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('goods')->truncate();
        factory(Good::class)->times(10000)->create();
    }
}
