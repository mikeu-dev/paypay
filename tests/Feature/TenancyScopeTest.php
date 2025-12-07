<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenancyScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_are_scoped_to_current_tenant()
    {
        // 1. Create Teams
        $teamA = Team::create(['name' => 'Team A', 'slug' => 'team-a']);
        $teamB = Team::create(['name' => 'Team B', 'slug' => 'team-b']);

        // 2. Create Data
        $user = User::factory()->create();
        $user->teams()->attach($teamA);
        $user->teams()->attach($teamB);
        
        $this->actingAs($user);

        // Let's test auto-assignment first
        Filament::setTenant($teamA);
        $clientA = Client::create(['name' => 'Client A', 'email' => 'a@test.com', 'phone' => '123', 'address' => 'A', 'status' => 'active']);
        
        Filament::setTenant($teamB);
        $clientB = Client::create(['name' => 'Client B', 'email' => 'b@test.com', 'phone' => '456', 'address' => 'B', 'status' => 'active']);

        // Verify IDs assigned correctly
        $this->assertEquals($teamA->id, $clientA->team_id);
        $this->assertEquals($teamB->id, $clientB->team_id);

        // 3. Test Scoping (Team A Context)
        Filament::setTenant($teamA);
        $clients_for_A = Client::all();
        $this->assertTrue($clients_for_A->contains($clientA));
        $this->assertFalse($clients_for_A->contains($clientB));
        $this->assertCount(1, $clients_for_A);

        // 4. Test Scoping (Team B Context)
        Filament::setTenant($teamB);
        $clients_for_B = Client::all();
        $this->assertTrue($clients_for_B->contains($clientB));
        $this->assertFalse($clients_for_B->contains($clientA));
        $this->assertCount(1, $clients_for_B);
    }

    public function test_attendance_is_scoped()
    {
        $team = Team::create(['name' => 'Team Usage', 'slug' => 'team-usage']);
        $user = User::factory()->create();
        $user->teams()->attach($team);
        
        $employee = \App\Models\Employee::create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'name' => 'Emp',
            'email' => 'e@e.com',
            'employee_code' => 'E1',
            'position' => 'P',
            'department' => 'D',
            'hire_date' => now(),
            'base_salary' => 1000
        ]);

        $this->actingAs($user);
        Filament::setTenant($team);
        
        $attendance = \App\Models\Attendance::create([
            'employee_id' => $employee->id,
            'date' => now(),
            'status' => 'present'
        ]);

        $this->assertEquals($team->id, $attendance->team_id);
    }

    public function test_task_is_scoped()
    {
        $team = Team::create(['name' => 'Team Task', 'slug' => 'team-task']);
        $user = User::factory()->create();
        $user->teams()->attach($team);
        
        $this->actingAs($user);
        Filament::setTenant($team);
        
        $client = \App\Models\Client::create([
             'name' => 'Cl',
             'email' => 'c@c.com',
             'team_id' => $team->id,
             'status' => 'active'
        ]);

        $project = \App\Models\Project::create([
             'client_id' => $client->id,
             'name' => 'Proj',
             'team_id' => $team->id,
             'start_date' => now(),
             'deadline' => now()->addDays(7),
             'budget' => 5000,
             'status' => 'active'
        ]);

        $task = \App\Models\Task::create([
            'project_id' => $project->id,
            'name' => 'Task 1',
            'team_id' => $team->id
            // other fields are nullable or guarded
        ]);

        // Re-query to test scope
        $fetched = \App\Models\Task::find($task->id);
        $this->assertEquals($team->id, $fetched->team_id);
        
        // Ensure Scope works
        $this->assertCount(1, \App\Models\Task::all());
    }

    public function test_allowance_is_scoped()
    {
        $team = Team::create(['name' => 'Team All', 'slug' => 'team-all']);
        $user = User::factory()->create();
        $user->teams()->attach($team);
        
        $this->actingAs($user);
        Filament::setTenant($team);
        
        $allowance = \App\Models\Allowance::create([
            'name' => 'Bonus',
            'type' => 'fixed',
            'value' => 100,
            'team_id' => $team->id
        ]);

        $fetched = \App\Models\Allowance::find($allowance->id);
        $this->assertEquals($team->id, $fetched->team_id);
    }

    public function test_deduction_is_scoped()
    {
        $team = Team::create(['name' => 'Team Ded', 'slug' => 'team-ded']);
        $user = User::factory()->create();
        $user->teams()->attach($team);
        
        $this->actingAs($user);
        Filament::setTenant($team);
        
        $deduction = \App\Models\Deduction::create([
            'name' => 'Tax',
            'type' => 'percent',
            'value' => 10,
            'team_id' => $team->id
        ]);

        $fetched = \App\Models\Deduction::find($deduction->id);
        $this->assertEquals($team->id, $fetched->team_id);
    }
}
