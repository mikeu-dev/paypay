<?php

namespace Tests\Feature;

use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PayrollTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_payroll_for_employee()
    {
        // 1. Create Employee
        $employee = Employee::create([
            'employee_code' => 'EMP001',
            'name' => 'John Doe',
            'position' => 'Developer',
            'base_salary' => 5000000,
        ]);

        // 2. Create Allowances and Deductions
        $transportAllowance = Allowance::create(['name' => 'Transport', 'type' => 'fixed', 'value' => 500000]);
        $mealAllowance = Allowance::create(['name' => 'Meal', 'type' => 'fixed', 'value' => 300000]);
        // Add a percentage deduction to test logic
        $taxDeduction = Deduction::create(['name' => 'Tax', 'type' => 'percentage', 'value' => 10]); // 10%

        // 3. Attach to Employee
        $employee->allowances()->attach($transportAllowance->id); // Use default value (500k)
        $employee->allowances()->attach($mealAllowance->id, ['amount' => 400000]); // Override value (400k)
        $employee->deductions()->attach($taxDeduction->id); // 10% of 5.000.000 = 500.000

        // 4. Generate Payroll
        $service = new PayrollService();
        $payroll = $service->generateForEmployee($employee, date('Y-m-01'), date('Y-m-t'));

        // 5. Verify Calculations
        // Base: 5.000.000
        // Allowances: 500.000 (Transport) + 400.000 (Meal override) = 900.000
        // Deductions: 500.000 (Tax 10%)
        // Net: 5.000.000 + 900.000 - 500.000 = 5.400.000

        $this->assertEquals(5000000, $payroll->basic_salary, 'Basic salary mismatch');
        $this->assertEquals(900000, $payroll->total_allowances, 'Total allowances mismatch');
        $this->assertEquals(500000, $payroll->total_deductions, 'Total deductions mismatch');
        $this->assertEquals(5400000, $payroll->net_salary, 'Net salary mismatch');

        // 6. Verify Database
        $this->assertDatabaseHas('payrolls', [
            'employee_id' => $employee->id,
            'net_salary' => 5400000,
        ]);

        $this->assertDatabaseHas('payroll_allowances', [
            'payroll_id' => $payroll->id,
            'name' => 'Transport',
            'amount' => 500000,
        ]);
        
        $this->assertDatabaseHas('payroll_allowances', [
            'payroll_id' => $payroll->id,
            'name' => 'Meal',
            'amount' => 400000,
        ]);
    }
}
