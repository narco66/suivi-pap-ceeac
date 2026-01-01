<?php

namespace App\Services;

use App\Models\User;
use App\Models\ActionPrioritaire;
use App\Models\Objectif;
use App\Models\Tache;
use App\Models\Kpi;
use App\Models\Alerte;
use Illuminate\Database\Eloquent\Builder;

/**
 * ReportQueryBuilder
 * 
 * Service pour construire des queries scoppées selon le rôle de l'utilisateur.
 * Garantit que les données récupérées respectent strictement le périmètre organisationnel.
 */
class ReportQueryBuilder
{
    protected User $user;
    protected ?int $departmentId = null;
    protected bool $isCommissaire = false;
    protected bool $isSecretaireGeneral = false;
    protected bool $isAdmin = false;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->isAdmin = $user->hasAnyRole(['admin', 'admin_dsi']);
        $this->isCommissaire = $user->isCommissaire() && !$this->isAdmin;
        $this->isSecretaireGeneral = $user->isSecretaireGeneral() && !$this->isAdmin;
        
        if ($this->isCommissaire) {
            $this->departmentId = $user->getDepartmentId();
        }
    }

    /**
     * Construire une query pour les actions prioritaires selon le périmètre
     */
    public function buildActionsQuery(): Builder
    {
        $query = ActionPrioritaire::query();

        if ($this->isCommissaire && $this->departmentId) {
            // Commissaire : uniquement son département
            $query->forDepartment($this->departmentId);
        } elseif ($this->isSecretaireGeneral) {
            // SG : uniquement Directions d'Appui
            $query->forAppui();
        } elseif (!$this->isAdmin) {
            // Par défaut, deny by default
            $query->whereRaw('1 = 0'); // Aucune donnée
        }

        return $query;
    }

    /**
     * Construire une query pour les objectifs selon le périmètre
     */
    public function buildObjectifsQuery(): Builder
    {
        $query = Objectif::query();

        if ($this->isCommissaire && $this->departmentId) {
            // Commissaire : objectifs ayant des actions de son département
            $query->whereHas('actionsPrioritaires', function($q) {
                $q->forDepartment($this->departmentId);
            });
        } elseif ($this->isSecretaireGeneral) {
            // SG : objectifs ayant des actions d'appui
            $query->whereHas('actionsPrioritaires', function($q) {
                $q->forAppui();
            });
        } elseif (!$this->isAdmin) {
            // Par défaut, deny by default
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    /**
     * Construire une query pour les tâches selon le périmètre
     */
    public function buildTachesQuery(): Builder
    {
        $query = Tache::query();

        if ($this->isCommissaire && $this->departmentId) {
            // Commissaire : uniquement son département
            $query->forDepartment($this->departmentId);
        } elseif ($this->isSecretaireGeneral) {
            // SG : uniquement Directions d'Appui
            $query->forAppui();
        } elseif (!$this->isAdmin) {
            // Par défaut, deny by default
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    /**
     * Construire une query pour les KPIs selon le périmètre
     */
    public function buildKpisQuery(): Builder
    {
        $query = Kpi::query();

        if ($this->isCommissaire && $this->departmentId) {
            // Commissaire : uniquement son département
            $query->forDepartment($this->departmentId);
        } elseif ($this->isSecretaireGeneral) {
            // SG : uniquement Directions d'Appui
            $query->forAppui();
        } elseif (!$this->isAdmin) {
            // Par défaut, deny by default
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    /**
     * Construire une query pour les alertes selon le périmètre
     */
    public function buildAlertesQuery(): Builder
    {
        $query = Alerte::query();

        if ($this->isCommissaire && $this->departmentId) {
            // Commissaire : uniquement son département
            $query->forDepartment($this->departmentId);
        } elseif ($this->isSecretaireGeneral) {
            // SG : uniquement Directions d'Appui
            $query->forAppui();
        } elseif (!$this->isAdmin) {
            // Par défaut, deny by default
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    /**
     * Déterminer le niveau de scope selon le rôle
     */
    public function getScopeLevel(): string
    {
        if ($this->isAdmin) {
            return 'GLOBAL';
        } elseif ($this->isSecretaireGeneral) {
            return 'SG';
        } elseif ($this->isCommissaire) {
            return 'COMMISSAIRE';
        }
        
        return 'NONE';
    }

    /**
     * Vérifier si l'utilisateur peut générer des rapports globaux
     */
    public function canGenerateGlobalReports(): bool
    {
        return $this->isAdmin || $this->user->hasAnyRole(['presidence', 'vice_presidence']);
    }

    /**
     * Obtenir l'ID du département (pour les commissaires)
     */
    public function getDepartmentId(): ?int
    {
        return $this->departmentId;
    }

    /**
     * Obtenir les IDs des Directions d'Appui (pour le SG)
     */
    public function getAppuiDirectionIds(): array
    {
        if ($this->isSecretaireGeneral) {
            return $this->user->getAppuiDirectionIds();
        }
        
        return [];
    }
}

