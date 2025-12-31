<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuditController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct()
    {
        // Le middleware est géré au niveau des routes
    }

    /**
     * Display a listing of audit logs
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', AuditLog::class);

        $query = AuditLog::with('actor');

        // Filtre par acteur
        if ($request->filled('actor_id')) {
            $query->where('actor_id', $request->actor_id);
        }

        // Filtre par action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filtre par module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filtre par objet
        if ($request->filled('object_type')) {
            $query->where('object_type', $request->object_type);
        }

        // Filtre par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('actor', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50);
        
        $actors = User::whereHas('auditLogs')->orderBy('name')->get();
        $actions = AuditLog::distinct()->pluck('action')->sort();
        $modules = AuditLog::distinct()->pluck('module')->filter()->sort();
        $objectTypes = AuditLog::distinct()->pluck('object_type')->filter()->sort();

        return view('admin.audit.index', compact('logs', 'actors', 'actions', 'modules', 'objectTypes'));
    }

    /**
     * Display the specified audit log
     */
    public function show(AuditLog $auditLog)
    {
        $this->authorize('view', $auditLog);

        $auditLog->load('actor');

        return view('admin.audit.show', compact('auditLog'));
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $this->authorize('export', AuditLog::class);

        // Même logique de filtrage que index
        $query = AuditLog::with('actor');
        
        // Appliquer les mêmes filtres...
        
        $logs = $query->orderBy('created_at', 'desc')->get();

        // Générer CSV
        $filename = 'audit_logs_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, ['Date', 'Acteur', 'Action', 'Module', 'Objet', 'Description', 'IP', 'User Agent']);
            
            // Données
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->actor?->name ?? 'Système',
                    $log->action,
                    $log->module ?? '-',
                    $log->object_type ? class_basename($log->object_type) : '-',
                    $log->description ?? '-',
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

