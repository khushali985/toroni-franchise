<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone_no',
        'date',
        'time',
        'no_of_people',
        'franchise_id',
        'table_id',
    ];

   
    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

   
    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }
}
