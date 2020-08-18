<?php


namespace App\Models;


use App\traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use Searchable;

    protected $table = 'goods';

    protected $casts = [
        'categories' => 'array',
    ];
}
