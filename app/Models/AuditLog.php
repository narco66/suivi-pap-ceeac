<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_id',
        'action',
        'object_type',
        'object_id',
        'metadata',
        'ip_address',
        'user_agent',
        'module',
        'description',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'acteur (utilisateur)
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Relation avec l'objet concerné (polymorphique)
     * Note: Cette relation nécessite que les modèles aient une relation inverse définie
     */
    public function object()
    {
        if (!$this->object_type || !$this->object_id) {
            return null;
        }
        
        return $this->object_type::find($this->object_id);
    }

    /**
     * Scope pour filtrer par action
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par module
     */
    public function scopeModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope pour filtrer par acteur
     */
    public function scopeActor($query, int $actorId)
    {
        return $query->where('actor_id', $actorId);
    }

    /**
     * Scope pour filtrer par objet
     */
    public function scopeObject($query, string $objectType, ?int $objectId = null)
    {
        $query->where('object_type', $objectType);
        
        if ($objectId) {
            $query->where('object_id', $objectId);
        }
        
        return $query;
    }
}

