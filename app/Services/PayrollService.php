<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollAllowance;
use App\Models\PayrollDeduction;
use App\Models\Attendance;
use Exception;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Generate payroll for a specific employee for a given period.
     *
     * @param Employee $employee
     * @param string $periodStart Y-m-d
     * @param string $periodEnd Y-m-d
     * @return Payroll
     * @throws Exception
     */
    public function generateForEmployee(Employee $employee, string $periodStart, string $periodEnd): Payroll
    {
        // Check if payroll already exists for this period
        $exists = Payroll::where('employee_id', $employee->id)
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->exists();

        if ($exists) {
            throw new Exception("Payroll for {$employee->name} already exists for this period.");
        }

        return DB::transaction(function () use ($employee, $periodStart, $periodEnd) {
            $baseSalary = $employee->base_salary;
            
            // Calculate Attendance Days
            $attendanceCount = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$periodStart, $periodEnd])
                ->whereIn('status', ['present', 'late'])
                ->count();

            // Calculate Allowances
            $totalAllowances = 0;
            $allowanceDetails = [];
            foreach ($employee->allowances as $allowance) {
                $type = $allowance->type;
                $override = $allowance->pivot->amount;
                $amount = 0;

                if ($type === 'daily') {
                    // For daily, override is treated as the DAILY RATE
                    $rate = $override ?? $allowance->value;
                    $amount = $rate * $attendanceCount;
                } elseif ($override !== null) {
                    // For others, override is the FINAL AMOUNT
                    $amount = $override;
                } else {
                    // Fallback to master value calculation
                    if ($type === 'percentage') {
                        $amount = $baseSalary * ($allowance->value / 100);
                    } else { // fixed
                        $amount = $allowance->value;
                    }
                }

                $totalAllowances += $amount;
                $allowanceDetails[] = [
                    'allowance_id' => $allowance->id,
                    'allowance_name' => $allowance->name,
                    'amount' => $amount,
                ];
            }

            // Calculate Deductions
            $totalDeductions = 0;
            $deductionDetails = [];
            foreach ($employee->deductions as $deduction) {
                $type = $deduction->type;
                $override = $deduction->pivot->amount;
                $amount = 0;

                if ($type === 'daily') {
                    $rate = $override ?? $deduction->value;
                    $amount = $rate * $attendanceCount;
                } elseif ($override !== null) {
                    $amount = $override;
                } else {
                    if ($type === 'percentage') {
                        $amount = $baseSalary * ($deduction->value / 100);
                    } else {
                        $amount = $deduction->value;
                    }
                }

                $totalDeductions += $amount;
                $deductionDetails[] = [
                    'deduction_id' => $deduction->id,
                    'deduction_name' => $deduction->name,
                    'amount' => $amount,
                ];
            }

            $netSalary = $baseSalary + $totalAllowances - $totalDeductions;

            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'basic_salary' => $baseSalary,
                'total_allowances' => $totalAllowances,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
            ]);

            // Save Details
            foreach ($allowanceDetails as $detail) {
                PayrollAllowance::create([
                    'payroll_id' => $payroll->id,
                    'name' => $detail['allowance_name'],
                    'amount' => $detail['amount'],
                ]);
            }

            foreach ($deductionDetails as $detail) {
                PayrollDeduction::create([
                    'payroll_id' => $payroll->id,
                    'name' => $detail['deduction_name'],
                    'amount' => $detail['amount'],
                ]);
            }

            return $payroll;
        });
    }

    public function generateBulk(string $periodStart, string $periodEnd, array $employeeIds = []): \Illuminate\Support\Collection
    {
        $query = Employee::query();
        
        if (!empty($employeeIds)) {
            $query->whereIn('id', $employeeIds);
        }

        $employees = $query->get();
        $payrolls = collect();

        foreach ($employees as $employee) {
            try {
                $payrolls->push($this->generateForEmployee($employee, $periodStart, $periodEnd));
            } catch (Exception $e) {
                // Skip if already exists or error
                continue;
            }
        }

        return $payrolls;
    }
}
