<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectionTechnique extends Model
{
    use HasFactory;
    
    protected $table = 'directions_techniques';
    
    protected $fillable = [
        'code',
        'libelle',
        'departement_id',
        'description',
        'actif',
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
}
