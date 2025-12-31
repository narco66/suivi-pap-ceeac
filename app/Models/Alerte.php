<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Alerte extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'type',
        'titre',
        'message',
        'criticite',
        'statut',
        'tache_id',
        'action_prioritaire_id',
        'niveau_escalade',
        'cree_par_id',
        'assignee_a_id',
        'date_creation',
        'date_resolution',
    ];
    
    protected $casts = [
        'date_creation' => 'datetime',
        'date_resolution' => 'datetime',
    ];
    
    public function tache()
    {
        return $this->belongsTo(Tache::class);
    }
    
    public function actionPrioritaire()
    {
        return $this->belongsTo(ActionPrioritaire::class);
    }
    
    public function creePar()
    {
        return $this->belongsTo(User::class, 'cree_par_id');
    }
    
    public function assigneeA()
    {
        return $this->belongsTo(User::class, 'assignee_a_id');
    }
}
