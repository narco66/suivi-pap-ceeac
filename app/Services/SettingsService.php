<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Récupérer une valeur de paramètre
     */
    public function get(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Définir une valeur de paramètre
     */
    public function set(string $key, $value, string $group = 'general', string $type = 'string', bool $encrypt = false): Setting
    {
        return Setting::set($key, $value, $group, $type, $encrypt);
    }

    /**
     * Récupérer toutes les valeurs d'un groupe
     */
    public function getGroup(string $group): array
    {
        return Setting::getGroup($group);
    }

    /**
     * Mettre à jour plusieurs paramètres d'un groupe
     */
    public function updateGroup(string $group, array $settings): void
    {
        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->where('group', $group)->first();
            
            if ($setting) {
                $setting->value = $setting->is_encrypted 
                    ? \Illuminate\Support\Facades\Crypt::encryptString($value) 
                    : $value;
                $setting->save();
            } else {
                Setting::set($key, $value, $group);
            }
        }
        
        Cache::forget("settings.group.{$group}");
    }

    /**
     * Vider le cache
     */
    public function clearCache(): void
    {
        Setting::clearCache();
    }
}



