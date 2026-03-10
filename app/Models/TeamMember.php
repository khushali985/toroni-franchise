<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $table = 'team_members';

    protected $fillable = [
        'franchise_id',
        'name',
        'role',
        'image',
        'description'
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }
}
