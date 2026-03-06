<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'cust_name',
        'review_text',
        'franchise_id',
        'rating'
    ];

     // Each Review belongs to one Franchise
    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }
}