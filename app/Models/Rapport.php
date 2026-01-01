<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Rapport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'titre',
        'description',
        'type',
        'scope_level',
        'format',
        'periode',
        'date_debut',
        'date_fin',
        'filtres',
        'parametres',
        'statut',
        'fichier_genere',
        'checksum',
        'taille_fichier',
        'date_generation',
        'date_envoi',
        'est_automatique',
        'frequence_cron',
        'destinataires',
        'notes',
        'cree_par_id',
        'papa_id',
        'objectif_id',
        'nombre_vues',
        'nombre_telechargements',
    ];

    protected $casts = [
        'filtres' => 'array',
        'parametres' => 'array',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_generation' => 'datetime',
        'date_envoi' => 'datetime',
        'est_automatique' => 'boolean',
        'nombre_vues' => 'integer',
        'nombre_telechargements' => 'integer',
    ];

    // Relations
    public function creePar()
    {
        return $this->belongsTo(User::class, 'cree_par_id');
    }

    public function papa()
    {
        return $this->belongsTo(Papa::class);
    }

    public function objectif()
    {
        return $this->belongsTo(Objectif::class);
    }

    // Scopes
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopeGeneres($query)
    {
        return $query->where('statut', 'genere');
    }

    public function scopeAutomatiques($query)
    {
        return $query->where('est_automatique', true);
    }

    public function scopeParPeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    // Accessors
    public function getTailleFichierFormateeAttribute()
    {
        if (!$this->taille_fichier) {
            return '-';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->taille_fichier;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getEstDisponibleAttribute()
    {
        return $this->statut === 'genere' && $this->fichier_genere && Storage::exists($this->fichier_genere);
    }

    // Méthodes
    public function incrementerVues()
    {
        $this->increment('nombre_vues');
    }

    public function incrementerTelechargements()
    {
        $this->increment('nombre_telechargements');
    }

    public function marquerCommeGenere($fichierPath, $taille = null)
    {
        // Si la taille n'est pas fournie, la récupérer depuis Storage
        if ($taille === null && Storage::exists($fichierPath)) {
            $taille = Storage::size($fichierPath);
        }
        
        $this->update([
            'statut' => 'genere',
            'fichier_genere' => $fichierPath,
            'taille_fichier' => $taille ?? 0,
            'date_generation' => now(),
        ]);
    }
}
