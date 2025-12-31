<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class Ressource extends Model
{
    use HasFactory;
    
    // SoftDeletes sera ajouté dynamiquement seulement si la table existe
    protected $dates = ['deleted_at'];

    /**
     * Méthode helper pour vérifier si on peut utiliser le modèle
     */
    public static function tableExists(): bool
    {
        try {
            return Schema::hasTable('ressources');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Boot du modèle - gérer SoftDeletes conditionnellement
     */
    public static function boot()
    {
        parent::boot();
        
        // Si la table existe, utiliser SoftDeletes
        if (static::tableExists()) {
            static::addGlobalScope('softDeletes', function ($builder) {
                $builder->whereNull('deleted_at');
            });
        }
    }

    /**
     * Override query() pour empêcher toute requête si la table n'existe pas
     */
    public static function query()
    {
        if (!static::tableExists()) {
            // Retourner un builder Eloquent qui ne fera jamais de requête
            $instance = new static;
            $connection = $instance->getConnection();
            
            // Créer un query builder qui retournera toujours une collection vide
            $queryBuilder = new class($connection, $connection->getQueryGrammar(), $connection->getPostProcessor()) extends \Illuminate\Database\Query\Builder {
                public function get($columns = ['*']) {
                    return collect([]);
                }
                public function count($columns = '*') {
                    return 0;
                }
                public function exists() {
                    return false;
                }
                public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null, $total = null) {
                    return new \Illuminate\Pagination\LengthAwarePaginator(
                        collect([]),
                        0,
                        $perPage,
                        $page ?? 1,
                        ['path' => request()->url(), 'query' => request()->query()]
                    );
                }
            };
            
            $queryBuilder->from($instance->getTable());
            
            // Créer le builder Eloquent avec le modèle associé
            $eloquentBuilder = $instance->newEloquentBuilder($queryBuilder);
            $eloquentBuilder->setModel($instance);
            
            return $eloquentBuilder;
        }
        
        return parent::query();
    }

    /**
     * Override resolveRouteBinding pour permettre le model binding même si query() est modifié
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Si la table n'existe pas, lever une exception
        if (!static::tableExists()) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
        }
        
        // Utiliser la méthode parente normale pour le binding
        $field = $field ?: $this->getRouteKeyName();
        
        return $this->where($field, $value)->firstOrFail();
    }

    protected $fillable = [
        'titre',
        'description',
        'type',
        'categorie',
        'version',
        'fichier',
        'nom_fichier_original',
        'taille_fichier',
        'mime_type',
        'est_public',
        'est_actif',
        'nombre_telechargements',
        'cree_par_id',
        'date_publication',
    ];

    protected $casts = [
        'est_public' => 'boolean',
        'est_actif' => 'boolean',
        'nombre_telechargements' => 'integer',
        'taille_fichier' => 'integer',
        'date_publication' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur créateur
     */
    public function creePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par_id');
    }

    /**
     * Obtenir l'URL de téléchargement du fichier
     */
    public function getUrlTelechargementAttribute(): ?string
    {
        if (!$this->fichier) {
            return null;
        }

        return route('ressources.download', $this->id);
    }

    /**
     * Vérifier si le fichier existe
     */
    public function fichierExists(): bool
    {
        if (!$this->fichier) {
            return false;
        }

        return Storage::disk('public')->exists($this->fichier);
    }

    /**
     * Obtenir la taille formatée du fichier
     */
    public function getTailleFormateeAttribute(): string
    {
        if (!$this->taille_fichier) {
            return 'N/A';
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

    /**
     * Obtenir l'icône selon le type
     */
    public function getIconeAttribute(): string
    {
        return match($this->type) {
            'excel' => 'bi-file-earmark-excel text-success',
            'pdf' => 'bi-file-earmark-pdf text-danger',
            'zip' => 'bi-file-earmark-zip text-warning',
            'doc', 'docx' => 'bi-file-earmark-word text-primary',
            'image' => 'bi-file-earmark-image text-info',
            default => 'bi-file-earmark text-secondary',
        };
    }

    /**
     * Scope pour les ressources publiques
     */
    public function scopePublic($query)
    {
        return $query->where('est_public', true);
    }

    /**
     * Scope pour les ressources actives
     */
    public function scopeActive($query)
    {
        return $query->where('est_actif', true);
    }

    /**
     * Scope pour une catégorie spécifique
     */
    public function scopeCategorie($query, string $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Scope pour un type spécifique
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Incrémenter le compteur de téléchargements
     */
    public function incrementerTelechargements(): void
    {
        $this->increment('nombre_telechargements');
    }
}

