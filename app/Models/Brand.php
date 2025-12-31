<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'brand_name', 'detail', 'image', 'price'];
    protected $table = 'brands';
}
