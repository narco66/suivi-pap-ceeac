<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AuditService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        protected AuditService $auditService,
        protected SettingsService $settingsService
    ) {
        // Le middleware est géré au niveau des routes
    }

    /**
     * Display a listing of settings
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Setting::class);

        $query = Setting::query();

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $settings = $query->orderBy('group')->orderBy('key')->paginate(20);
        $groups = Setting::distinct()->pluck('group')->sort();

        return view('admin.settings.index', compact('settings', 'groups'));
    }

    /**
     * Show the form for editing settings by group
     */
    public function editGroup(string $group)
    {
        $this->authorize('update', Setting::class);

        $settings = Setting::where('group', $group)->get()->keyBy('key');
        $groupLabels = [
            'general' => 'Paramètres généraux',
            'business' => 'Paramètres métiers',
            'notifications' => 'Paramètres de notifications',
            'retention' => 'Paramètres de rétention',
        ];

        return view('admin.settings.edit-group', compact('settings', 'group', 'groupLabels'));
    }

    /**
     * Update settings by group
     */
    public function updateGroup(Request $request, string $group)
    {
        $this->authorize('update', Setting::class);

        $validated = $request->validate([
            'settings' => ['required', 'array'],
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = Setting::where('key', $key)->where('group', $group)->first();
            
            if ($setting) {
                $oldValue = $setting->value;
                $setting->value = $setting->is_encrypted 
                    ? \Illuminate\Support\Facades\Crypt::encryptString($value) 
                    : $value;
                $setting->save();

                $this->auditService->log('updated', $setting, [
                    'key' => $key,
                    'old_value' => $oldValue,
                    'new_value' => $value,
                ], 'admin', "Modification du paramètre {$key}");
            }
        }

        $this->settingsService->clearCache();

        return redirect()->route('admin.settings.index', ['group' => $group])
            ->with('success', 'Paramètres mis à jour avec succès.');
    }

    /**
     * Update a single setting
     */
    public function update(Request $request, Setting $setting)
    {
        $this->authorize('update', $setting);

        $validated = $request->validate([
            'value' => ['required'],
            'description' => ['nullable', 'string'],
        ]);

        $oldValue = $setting->value;
        $setting->value = $setting->is_encrypted 
            ? \Illuminate\Support\Facades\Crypt::encryptString($validated['value']) 
            : $validated['value'];
        
        if (isset($validated['description'])) {
            $setting->description = $validated['description'];
        }
        
        $setting->save();

        $this->settingsService->clearCache();
        $this->auditService->log('updated', $setting, [
            'old_value' => $oldValue,
            'new_value' => $validated['value'],
        ], 'admin', "Modification du paramètre {$setting->key}");

        return redirect()->back()
            ->with('success', 'Paramètre mis à jour avec succès.');
    }
}

