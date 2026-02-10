<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'franchise_id',
        'dish_name',
        'ingrediants',
        'price',
        'category',
        'image'
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }
}
