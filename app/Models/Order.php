<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'franchise_id',
        'full_name',
        'phone',
        'address',
        'items',
        'email',
        'total'
    ];

    protected $casts = [
        'items' => 'array'
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }
}
