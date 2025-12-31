<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Non authentifié');
        }

        // Les admins DSI et admin ont accès complet
        if ($user->hasAnyRole(['admin_dsi', 'admin'])) {
            return $next($request);
        }

        // Vérifier la permission admin.access
        if ($user->hasPermissionTo('admin.access')) {
            return $next($request);
        }

        abort(403, 'Vous n\'avez pas les permissions nécessaires pour accéder à cette section.');
    }
}


