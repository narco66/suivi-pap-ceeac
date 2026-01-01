<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Structure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'parent_id',
        'description',
        'email',
        'phone',
        'address',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relation avec la structure parente
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Structure::class, 'parent_id');
    }

    /**
     * Relation avec les structures enfants
     */
    public function children(): HasMany
    {
        return $this->hasMany(Structure::class, 'parent_id')->orderBy('order');
    }

    /**
     * Relation avec les utilisateurs
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope pour les structures actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour un type spécifique
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}



