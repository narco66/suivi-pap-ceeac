<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Avancement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tache_id',
        'date_avancement',
        'pourcentage_avancement',
        'commentaire',
        'fichier_joint',
        'soumis_par_id',
        'valide_par_id',
        'date_validation',
        'statut',
    ];
    
    protected $casts = [
        'date_avancement' => 'datetime',
        'date_validation' => 'datetime',
        'pourcentage_avancement' => 'integer',
    ];
    
    public function tache()
    {
        return $this->belongsTo(Tache::class);
    }
    
    public function soumisPar()
    {
        return $this->belongsTo(\App\Models\User::class, 'soumis_par_id');
    }
    
    public function validePar()
    {
        return $this->belongsTo(\App\Models\User::class, 'valide_par_id');
    }
    
    /**
     * Obtenir l'URL du fichier joint
     */
    public function getFichierJointUrlAttribute()
    {
        if (!$this->fichier_joint) {
            return null;
        }
        
        // Si c'est un chemin absolu Windows, on ne peut pas le servir directement
        if (strpos($this->fichier_joint, 'C:/') === 0 || strpos($this->fichier_joint, 'C:\\') === 0) {
            // C'est un fichier temporaire qui n'existe probablement plus
            return null;
        }
        
        // Si c'est un chemin relatif au storage
        if (strpos($this->fichier_joint, 'storage/') === 0 || strpos($this->fichier_joint, 'public/') === 0) {
            return asset($this->fichier_joint);
        }
        
        // Sinon, on assume que c'est un chemin relatif au storage/app/public
        return asset('storage/' . $this->fichier_joint);
    }
    
    /**
     * Vérifier si le fichier joint existe
     */
    public function fichierJointExists()
    {
        if (!$this->fichier_joint) {
            return false;
        }
        
        // Si c'est un chemin absolu Windows, on ne peut pas le vérifier
        if (strpos($this->fichier_joint, 'C:/') === 0 || strpos($this->fichier_joint, 'C:\\') === 0) {
            return false;
        }
        
        // Vérifier si le fichier existe dans le storage
        $path = str_replace('storage/', '', $this->fichier_joint);
        $path = str_replace('public/', '', $path);
        return Storage::disk('public')->exists($path);
    }
}
