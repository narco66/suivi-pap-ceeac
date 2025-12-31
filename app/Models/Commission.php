<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code',
        'libelle',
        'description',
        'actif',
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];

    public function commissaires()
    {
        return $this->hasMany(Commissaire::class);
    }
}
