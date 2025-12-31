<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'papa_version_id',
        'code',
        'libelle',
        'description',
        'statut',
        'priorite',
        'date_debut_prevue',
        'date_fin_prevue',
        'date_debut_reelle',
        'date_fin_reelle',
        'pourcentage_avancement',
    ];
    
    protected $casts = [
        'date_debut_prevue' => 'datetime',
        'date_fin_prevue' => 'datetime',
        'date_debut_reelle' => 'datetime',
        'date_fin_reelle' => 'datetime',
        'pourcentage_avancement' => 'integer',
    ];
    
    public function papaVersion()
    {
        return $this->belongsTo(PapaVersion::class);
    }
    
    public function actionsPrioritaires()
    {
        return $this->hasMany(ActionPrioritaire::class);
    }
}
