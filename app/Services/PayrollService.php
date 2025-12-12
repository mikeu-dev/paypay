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
     * Menghasilkan data payroll untuk seorang karyawan dalam satu periode tertentu.
     *
     * @param Employee $employee   Data karyawan
     * @param string   $periodStart  Tanggal mulai periode (format: Y-m-d)
     * @param string   $periodEnd    Tanggal akhir periode (format: Y-m-d)
     *
     * @return Payroll
     * @throws Exception Jika payroll periode tersebut sudah ada
     */
    public function generateForEmployee(Employee $employee, string $periodStart, string $periodEnd): Payroll
    {
        // Cek apakah payroll untuk periode ini sudah pernah dibuat
        $exists = Payroll::where('employee_id', $employee->id)
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->exists();

        if ($exists) {
            throw new Exception("Payroll untuk {$employee->name} sudah dibuat pada periode ini.");
        }

        return DB::transaction(function () use ($employee, $periodStart, $periodEnd) {

            // Gaji pokok dari tabel karyawan
            $baseSalary = $employee->base_salary;
            
            /**
             * Hitung jumlah hari kehadiran
             * hanya 'present' dan 'late' yang dihitung sebagai hadir
             */
            $attendanceCount = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$periodStart, $periodEnd])
                ->whereIn('status', ['present', 'late'])
                ->count();

            /**
             * ============================
             *  PERHITUNGAN TUNJANGAN
             * ============================
             *
             * Catatan aturan:
             * - type = "daily" → nominal dihitung per hari hadir
             * - override (pivot->amount) diperlakukan sebagai:
             *      → rate harian untuk type "daily"
             *      → nilai final untuk type lain
             * - Jika tidak ada override:
             *      → percentage: persentase dari gaji pokok
             *      → fixed: nominal tetap
             */
            $totalAllowances = 0;
            $allowanceDetails = [];

            foreach ($employee->allowances as $allowance) {
                $type = $allowance->type;
                $override = $allowance->pivot->amount;
                $amount = 0;

                if ($type === 'daily') {
                    $rate = $override ?? $allowance->value;
                    $amount = $rate * $attendanceCount;
                } elseif ($override !== null) {
                    $amount = $override;
                } else {
                    if ($type === 'percentage') {
                        $amount = $baseSalary * ($allowance->value / 100);
                    } else {
                        $amount = $allowance->value;
                    }
                }

                $totalAllowances += $amount;

                $allowanceDetails[] = [
                    'allowance_id'   => $allowance->id,
                    'allowance_name' => $allowance->name,
                    'amount'         => $amount,
                ];
            }

            /**
             * ============================
             *  PERHITUNGAN POTONGAN
             * ============================
             *
             * Mengikuti aturan yang sama seperti tunjangan,
             * hanya saja nilainya mengurangi gaji.
             */
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
                    'deduction_id'   => $deduction->id,
                    'deduction_name' => $deduction->name,
                    'amount'         => $amount,
                ];
            }

            /**
             * Gaji bersih = gaji pokok + total tunjangan – total potongan
             */
            $netSalary = $baseSalary + $totalAllowances - $totalDeductions;

            // Simpan header payroll
            $payroll = Payroll::create([
                'employee_id'      => $employee->id,
                'period_start'     => $periodStart,
                'period_end'       => $periodEnd,
                'basic_salary'     => $baseSalary,
                'total_allowances' => $totalAllowances,
                'total_deductions' => $totalDeductions,
                'net_salary'       => $netSalary,
            ]);

            // Simpan detail tunjangan
            foreach ($allowanceDetails as $detail) {
                PayrollAllowance::create([
                    'payroll_id' => $payroll->id,
                    'name'       => $detail['allowance_name'],
                    'amount'     => $detail['amount'],
                ]);
            }

            // Simpan detail potongan
            foreach ($deductionDetails as $detail) {
                PayrollDeduction::create([
                    'payroll_id' => $payroll->id,
                    'name'       => $detail['deduction_name'],
                    'amount'     => $detail['amount'],
                ]);
            }

            return $payroll;
        });
    }

    /**
     * Menghasilkan payroll untuk banyak karyawan sekaligus dalam satu periode.
     *
     * @param string $periodStart  Tanggal mulai (Y-m-d)
     * @param string $periodEnd    Tanggal selesai (Y-m-d)
     * @param array  $employeeIds  (opsional) list ID karyawan yang ingin diproses
     *
     * @return \Illuminate\Support\Collection
     */
    public function generateBulk(string $periodStart, string $periodEnd, array $employeeIds = []): \Illuminate\Support\Collection
    {
        $query = Employee::query();

        // Jika ID karyawan ditentukan, maka hanya proses mereka
        if (!empty($employeeIds)) {
            $query->whereIn('id', $employeeIds);
        }

        $employees = $query->get();
        $payrolls = collect();

        foreach ($employees as $employee) {
            try {
                $payrolls->push(
                    $this->generateForEmployee($employee, $periodStart, $periodEnd)
                );
            } catch (Exception $e) {
                // Abaikan jika payroll sudah ada atau terjadi error lain
                continue;
            }
        }

        return $payrolls;
    }
}
