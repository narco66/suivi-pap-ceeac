<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SidebarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer les permissions nécessaires
        Permission::firstOrCreate(['name' => 'admin.access', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'gantt.view', 'guard_name' => 'web']);
        
        // Créer les rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(['admin.access', 'gantt.view']);
    }

    public function test_admin_user_sees_administration_menu(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Administration', false);
    }

    public function test_standard_user_does_not_see_administration_menu(): void
    {
        $user = User::factory()->create();
        // Pas de rôle admin

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertDontSee('Administration', false);
    }

    public function test_sidebar_displays_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Tableau de bord', false);
        $response->assertSee('sidebar-ceeac', false);
    }

    public function test_sidebar_not_displayed_for_guests(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}


