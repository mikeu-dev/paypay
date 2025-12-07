<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_logs_model_events()
    {
        // 1. Create a User (Actor)
        $user = User::factory()->create();

        // 2. Act as User
        $this->actingAs($user);

        // 3. Create an Employee (Subject)
        $employee = Employee::factory()->create([
            'name' => 'John Doe',
        ]);

        // 4. Update Employee
        $employee->update(['name' => 'Jane Doe']);

        // 5. Verify Logs
        // Expect at least 2 logs: 1 for creation (if 'logAll' includes creation), 1 for update.
        // Spatie `logAll` usually covers create, update, delete.
        
        $this->assertDatabaseCount('activity_log', 3); 
        // 1 for User creation (maybe, if user logging is on and no actor yet)
        // 1 for Employee creation
        // 1 for Employee update

        // Fetch the specific log for the employee update
        $updateLog = Activity::where('subject_type', Employee::class)
            ->where('description', 'updated')
            ->first();

        $this->assertNotNull($updateLog, 'Employee update log not found.');
        $this->assertEquals($employee->id, $updateLog->subject_id);
        $this->assertEquals($user->id, $updateLog->causer_id);
        
        // check changes
        $this->assertArrayHasKey('name', $updateLog->properties['attributes']);
        $this->assertEquals('Jane Doe', $updateLog->properties['attributes']['name']);
    }
}
