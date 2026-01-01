<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class NavigationHelper
{
    /**
     * Vérifie si un item de navigation doit être affiché
     */
    public static function shouldShowItem(array $item): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Vérifier la permission
        if (isset($item['permission']) && $item['permission']) {
            try {
                if (!$user->can($item['permission'])) {
                    return false;
                }
            } catch (\Exception $e) {
                // Permission n'existe pas, ne pas afficher
                return false;
            }
        }

        // Vérifier le rôle (alternative)
        if (isset($item['role']) && $item['role']) {
            $roles = is_array($item['role']) ? $item['role'] : [$item['role']];
            $hasRole = false;
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    $hasRole = true;
                    break;
                }
            }
            if (!$hasRole) {
                return false;
            }
        }

        // Vérifier que la route existe
        if (isset($item['route']) && $item['route']) {
            if (!Route::has($item['route'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Vérifie si un item est actif
     */
    public static function isActive(array $item): bool
    {
        if (isset($item['active']) && is_array($item['active'])) {
            foreach ($item['active'] as $pattern) {
                if (request()->routeIs($pattern)) {
                    return true;
                }
            }
        }

        if (isset($item['route']) && $item['route']) {
            return request()->routeIs($item['route']);
        }

        // Vérifier les enfants
        if (isset($item['children']) && is_array($item['children'])) {
            foreach ($item['children'] as $child) {
                if (self::isActive($child)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Récupère le badge pour un item (compteur, etc.)
     */
    public static function getBadge(array $item): ?string
    {
        if (!isset($item['badge'])) {
            return null;
        }

        // Exemple : 'alertes.count' => récupérer le nombre d'alertes
        // À implémenter selon les besoins
        return null;
    }
}


