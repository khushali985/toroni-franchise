<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    /**
     * Table name in database
     */
    protected $table = 'stories';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'status'
    ];
}

