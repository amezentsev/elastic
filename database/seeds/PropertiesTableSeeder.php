<?php


use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertiesTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('properties')->truncate();
        factory(Property::class)->times(50)->create();
    }
}
