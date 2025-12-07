<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure roles exist
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        $this->seed(\Database\Seeders\TeamSeeder::class); // For default team
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function test_administrator_can_access_employees()
    {
        $user = User::factory()->create();
        $user->assignRole('Administrator');
        $this->assertTrue($user->can('viewAny', \App\Models\Employee::class));
    }

    public function test_finance_can_access_payrolls()
    {
        $user = User::factory()->create();
        $user->assignRole('Finance');
        $this->assertTrue($user->can('viewAny', \App\Models\Payroll::class));
    }

    public function test_finance_can_access_invoices()
    {
        $user = User::factory()->create();
        $user->assignRole('Finance');
        $this->assertTrue($user->can('viewAny', \App\Models\Invoice::class));
    }

    public function test_finance_cannot_access_projects()
    {
        $user = User::factory()->create();
        $user->assignRole('Finance');
        $this->assertFalse($user->can('viewAny', \App\Models\Project::class));
    }

    public function test_operations_can_access_projects()
    {
        $user = User::factory()->create();
        $user->assignRole('Operations');
        $this->assertTrue($user->can('viewAny', \App\Models\Project::class));
    }
    
    public function test_operations_can_access_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Operations');
        $this->assertTrue($user->can('viewAny', \App\Models\Task::class));
    }
    
    public function test_operations_can_access_clients()
    {
        $user = User::factory()->create();
        $user->assignRole('Operations');
        $this->assertTrue($user->can('viewAny', \App\Models\Client::class));
    }
}
