<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SystemHealthController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        // Le middleware est géré au niveau des routes
    }

    /**
     * Display system health information
     */
    public function index()
    {
        $health = [
            'app' => [
                'name' => config('app.name'),
                'env' => config('app.env'),
                'debug' => config('app.debug'),
                'version' => '1.0.0', // À définir selon votre versioning
            ],
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
            'storage' => $this->checkStorage(),
            'mail' => $this->checkMail(),
        ];

        return view('admin.system.health', compact('health'));
    }

    protected function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $status = 'ok';
            $message = 'Connexion réussie';
        } catch (\Exception $e) {
            $status = 'error';
            $message = $e->getMessage();
        }

        return [
            'status' => $status,
            'message' => $message,
            'driver' => config('database.default'),
        ];
    }

    protected function checkCache(): array
    {
        try {
            Cache::put('health_check', 'ok', 10);
            $value = Cache::get('health_check');
            $status = $value === 'ok' ? 'ok' : 'warning';
            $message = $value === 'ok' ? 'Fonctionnel' : 'Problème de lecture';
        } catch (\Exception $e) {
            $status = 'error';
            $message = $e->getMessage();
        }

        return [
            'status' => $status,
            'message' => $message,
            'driver' => config('cache.default'),
        ];
    }

    protected function checkQueue(): array
    {
        return [
            'status' => 'info',
            'message' => 'Vérification manuelle requise',
            'driver' => config('queue.default'),
        ];
    }

    protected function checkStorage(): array
    {
        try {
            $disk = Storage::disk('public');
            $total = disk_total_space($disk->path(''));
            $free = disk_free_space($disk->path(''));
            $used = $total - $free;
            $percent = ($used / $total) * 100;

            $status = $percent > 90 ? 'warning' : ($percent > 95 ? 'error' : 'ok');
            
            return [
                'status' => $status,
                'message' => number_format($percent, 2) . '% utilisé',
                'total' => $this->formatBytes($total),
                'used' => $this->formatBytes($used),
                'free' => $this->formatBytes($free),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function checkMail(): array
    {
        return [
            'status' => 'info',
            'message' => 'Configuration: ' . config('mail.default'),
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host') ?? 'N/A',
        ];
    }

    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

