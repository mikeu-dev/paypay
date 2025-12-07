<?php

namespace Tests\Feature;

use App\Models\Allowance;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\PayrollService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendancePayrollTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_calculate_daily_allowance_based_on_attendance()
    {
        // 1. Create Employee
        $employee = Employee::create([
            'employee_code' => 'EMP002',
            'name' => 'Jane Agent',
            'position' => 'Staff',
            'base_salary' => 3000000,
        ]);

        // 2. Create 'Daily' Allowance
        $mealAllowance = Allowance::create(['name' => 'Meal Daily', 'type' => 'daily', 'value' => 50000]); // 50k per day

        // 3. Attach to Employee
        $employee->allowances()->attach($mealAllowance->id);

        // 4. Create Attendance Records (3 days present, 1 absent)
        Attendance::create(['employee_id' => $employee->id, 'date' => date('Y-m-01'), 'status' => 'present']);
        Attendance::create(['employee_id' => $employee->id, 'date' => date('Y-m-02'), 'status' => 'present']);
        Attendance::create(['employee_id' => $employee->id, 'date' => date('Y-m-03'), 'status' => 'present']);
        Attendance::create(['employee_id' => $employee->id, 'date' => date('Y-m-04'), 'status' => 'absent']); // Should not count

        // 5. Generate Payroll
        $service = new PayrollService();
        $payroll = $service->generateForEmployee($employee, date('Y-m-01'), date('Y-m-t'));

        // 6. Verify Logic
        // Base: 3.000.000
        // Allowance: 3 days * 50.000 = 150.000
        // Net: 3.150.000

        $this->assertEquals(3000000, $payroll->basic_salary);
        $this->assertEquals(150000, $payroll->total_allowances);
        $this->assertEquals(3150000, $payroll->net_salary);

        // Verify Detail
        $this->assertDatabaseHas('payroll_allowances', [
            'payroll_id' => $payroll->id,
            'name' => 'Meal Daily',
            'amount' => 150000,
        ]);
    }
}
