<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'matricule',
        'telephone',
        'fonction',
        'actif',
        'phone',
        'title',
        'status',
        'structure_id',
        'last_login_at',
        'avatar',
        'metadata',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * Relation avec la structure
     */
    public function structure()
    {
        return $this->belongsTo(\App\Models\Structure::class);
    }

    /**
     * Vérifier si l'utilisateur est actif
     */
    public function isActive(): bool
    {
        $status = $this->status ?? ($this->actif ? 'actif' : 'inactif');
        return $status === 'actif';
    }

    /**
     * Vérifier si l'utilisateur est suspendu
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspendu';
    }

    /**
     * Mettre à jour la date de dernière connexion
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Relation avec les logs d'audit (en tant qu'acteur)
     */
    public function auditLogs()
    {
        return $this->hasMany(\App\Models\AuditLog::class, 'actor_id');
    }

    /**
     * Relation 1-1 avec le Département (si l'utilisateur est commissaire)
     * Un user ne peut être commissaire que d'un seul département
     */
    public function departement()
    {
        return $this->hasOne(Departement::class, 'commissioner_user_id');
    }

    /**
     * Vérifier si l'utilisateur est un commissaire
     */
    public function isCommissaire(): bool
    {
        return $this->hasRole('commissaire') && $this->departement !== null;
    }

    /**
     * Récupérer l'ID du département du commissaire
     * Retourne null si l'utilisateur n'est pas commissaire
     */
    public function getDepartmentId(): ?int
    {
        if (!$this->isCommissaire()) {
            return null;
        }
        
        return $this->departement?->id;
    }

    /**
     * Vérifier si l'utilisateur est Secrétaire Général
     */
    public function isSecretaireGeneral(): bool
    {
        return $this->hasRole('secretaire_general');
    }

    /**
     * Récupérer toutes les Directions d'Appui (pour le SG)
     * Le SG a autorité sur TOUTES les Directions d'Appui
     */
    public function getAppuiDirections()
    {
        if (!$this->isSecretaireGeneral()) {
            return collect([]);
        }

        // Le SG a autorité sur TOUTES les Directions d'Appui
        return \App\Models\DirectionAppui::where('actif', true)->get();
    }

    /**
     * Récupérer les IDs des Directions d'Appui (pour le SG)
     */
    public function getAppuiDirectionIds(): array
    {
        if (!$this->isSecretaireGeneral()) {
            return [];
        }

        return \App\Models\DirectionAppui::where('actif', true)->pluck('id')->toArray();
    }
}
