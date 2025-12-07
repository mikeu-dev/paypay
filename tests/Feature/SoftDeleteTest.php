<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoftDeleteTest extends TestCase
{
    // We cannot use RefreshDatabase because we want to test persistence against the actual modified schema
    // But since we are in a test environment, it's safer to use transactions or proper setup/teardown.
    // However, DatabaseSeeder might have run. Let's use RefreshDatabase to be safe and clean.
    use RefreshDatabase;

    public function test_can_soft_delete_models()
    {
        // 1. Employee
        $employee = Employee::factory()->create();
        $id = $employee->id;
        $employee->delete();

        $this->assertSoftDeleted('employees', ['id' => $id]);

        // 2. User
        $user = User::factory()->create();
        $userId = $user->id;
        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);

        // 3. Attendance
        // Ensure employee exists for attendance (though factories should handle it)
        $emp2 = Employee::factory()->create();
        $attendance = Attendance::create([
            'employee_id' => $emp2->id,
            'date' => now(),
            'status' => 'present'
        ]);
        $attId = $attendance->id;
        $attendance->delete();

        $this->assertSoftDeleted('attendances', ['id' => $attId]);

        // 4. Payroll
        $payroll = Payroll::create([
            'employee_id' => $emp2->id,
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
            'basic_salary' => 1000,
            'total_allowances' => 0,
            'total_deductions' => 0,
            'net_salary' => 1000
        ]);
        $payrollId = $payroll->id;
        $payroll->delete();

        $this->assertSoftDeleted('payrolls', ['id' => $payrollId]);
    }
}
