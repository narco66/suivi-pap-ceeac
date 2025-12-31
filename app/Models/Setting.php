<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'is_encrypted',
        'description',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Récupérer une valeur de paramètre
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            $value = $setting->is_encrypted 
                ? Crypt::decryptString($setting->value) 
                : $setting->value;

            return self::castValue($value, $setting->type);
        });
    }

    /**
     * Définir une valeur de paramètre
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'string', bool $encrypt = false): self
    {
        $setting = self::firstOrNew(['key' => $key]);
        
        $setting->value = $encrypt 
            ? Crypt::encryptString($value) 
            : $value;
        $setting->group = $group;
        $setting->type = $type;
        $setting->is_encrypted = $encrypt;
        
        $setting->save();
        
        Cache::forget("setting.{$key}");
        
        return $setting;
    }

    /**
     * Caster la valeur selon le type
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Récupérer toutes les valeurs d'un groupe
     */
    public static function getGroup(string $group): array
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group) {
            return self::where('group', $group)
                ->get()
                ->mapWithKeys(function ($setting) {
                    $value = $setting->is_encrypted 
                        ? Crypt::decryptString($setting->value) 
                        : $setting->value;
                    
                    return [$setting->key => self::castValue($value, $setting->type)];
                })
                ->toArray();
        });
    }

    /**
     * Vider le cache des paramètres
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}


