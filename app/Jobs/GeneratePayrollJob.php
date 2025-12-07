<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\PayrollService;

class GeneratePayrollJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $periodStart,
        public string $periodEnd,
        public array $employeeIds = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(PayrollService $service): void
    {
        $payrolls = $service->generateBulk($this->periodStart, $this->periodEnd, $this->employeeIds);

        foreach ($payrolls as $payroll) {
            if ($payroll->employee && $payroll->employee->user) {
                try {
                    $payroll->employee->user->notify(new \App\Notifications\PayrollGenerated($payroll));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to notify employee {$payroll->employee->name}: " . $e->getMessage());
                }
            }
        }
    }
}
