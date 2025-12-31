<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectionAppui extends Model
{
    use HasFactory;
    
    protected $table = 'directions_appui';
    
    protected $fillable = [
        'code',
        'libelle',
        'description',
        'actif',
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];
}
