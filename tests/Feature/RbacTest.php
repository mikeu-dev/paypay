<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_super_admin_can_access_everything()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->actingAs($user);

        // Can access Employees
        $this->assertTrue($user->can('viewAny', Employee::class));
        $this->assertTrue($user->can('viewAny', Payroll::class));
    }

    public function test_finance_can_access_payroll_but_not_employees()
    {
        $user = User::factory()->create();
        $user->assignRole('Finance');

        $this->actingAs($user);

        $this->assertTrue($user->can('viewAny', Payroll::class));
        $this->assertFalse($user->can('viewAny', Employee::class));
    }

    public function test_admin_can_access_employees_but_not_payroll()
    {
        $user = User::factory()->create();
        $user->assignRole('Administrator');

        $this->actingAs($user);

        $this->assertTrue($user->can('viewAny', Employee::class));
        $this->assertFalse($user->can('viewAny', Payroll::class));
    }

    public function test_marketing_can_access_clients_only()
    {
        $user = User::factory()->create();
        $user->assignRole('Marketing');

        $this->actingAs($user);

        $this->assertTrue($user->can('viewAny', \App\Models\Client::class));
        $this->assertFalse($user->can('viewAny', Payroll::class));
    }
}
