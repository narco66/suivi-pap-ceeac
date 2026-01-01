<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'action_prioritaire_id',
        'code',
        'libelle',
        'description',
        'unite',
        'valeur_cible',
        'valeur_realisee',
        'valeur_ecart',
        'pourcentage_realisation',
        'date_mesure',
        'statut',
    ];
    
    protected $casts = [
        'valeur_cible' => 'decimal:2',
        'valeur_realisee' => 'decimal:2',
        'valeur_ecart' => 'decimal:2',
        'pourcentage_realisation' => 'decimal:2',
        'date_mesure' => 'datetime',
    ];
    
    public function actionPrioritaire()
    {
        return $this->belongsTo(ActionPrioritaire::class);
    }
    
    public function alertes()
    {
        return $this->hasMany(Alerte::class, 'action_prioritaire_id', 'action_prioritaire_id')
            ->where('type', 'kpi_non_atteint');
    }

    /**
     * Relation avec le département (via action prioritaire -> direction technique)
     * Utilise une relation indirecte via actionPrioritaire
     */
    public function departement()
    {
        // Relation indirecte : Kpi -> ActionPrioritaire -> DirectionTechnique -> Departement
        // On utilise une accessor plutôt qu'une relation Eloquent directe
        return $this->actionPrioritaire?->departement;
    }

    /**
     * Scope pour filtrer les KPIs par département
     */
    public function scopeForDepartment($query, ?int $departmentId)
    {
        if ($departmentId === null) {
            return $query->whereDoesntHave('actionPrioritaire.directionTechnique');
        }

        return $query->whereHas('actionPrioritaire.directionTechnique', function ($q) use ($departmentId) {
            $q->where('departement_id', $departmentId);
        });
    }

    /**
     * Récupérer l'ID du département du KPI
     */
    public function getDepartmentId(): ?int
    {
        return $this->actionPrioritaire?->getDepartmentId();
    }

    /**
     * Vérifier si le KPI est lié à une action d'appui
     */
    public function isAppui(): bool
    {
        return $this->actionPrioritaire?->isAppui() ?? false;
    }

    /**
     * Scope pour filtrer les KPIs d'appui uniquement
     */
    public function scopeForAppui($query)
    {
        return $query->whereHas('actionPrioritaire', function ($q) {
            $q->whereNotNull('direction_appui_id');
        });
    }
}
