<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $table = 'restaurant_tables';

    protected $fillable = [
        'franchise_id',
        'table_no',
        'capacity_people',
        'status'
    ];

    
    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }
}
