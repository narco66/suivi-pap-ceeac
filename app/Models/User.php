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
}
