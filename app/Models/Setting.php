<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'email',
        'contact',
        'address',
        'opening_hours',
        'logo'
    ];
}
