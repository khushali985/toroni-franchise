<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'franchise_id',
        'dish_name',
        'ingredients',
        'price',
        'category',
        'image'
    ];

    

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }
}
