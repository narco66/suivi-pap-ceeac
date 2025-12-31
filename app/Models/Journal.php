<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    
    protected $table = 'journaux';

    protected $fillable = [
        'action',
        'entite_type',
        'entite_id',
        'utilisateur_id',
        'description',
        'donnees_avant',
        'donnees_apres',
        'ip_address',
        'user_agent',
    ];
    
    protected $casts = [
        'donnees_avant' => 'array',
        'donnees_apres' => 'array',
    ];
}
