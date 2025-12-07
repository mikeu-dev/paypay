<?php

namespace Tests\Feature;

use App\Filament\Resources\Payrolls\Pages\ListPayrolls;
use App\Jobs\GeneratePayrollJob;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class PayrollQueueTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_can_dispatch_payroll_generation_job()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        Queue::fake();

        Livewire::test(ListPayrolls::class)
            // 'generate' action is a header action (page action), so we use mountAction + callMountedAction
            // OR checks generic 'callAction'.
            // Let's try mountAction first because it has a form.
            ->mountAction('generate')
            ->setMountedActionData([
                'period_start' => '2025-01-01',
                'period_end' => '2025-01-31',
            ])
            ->callMountedAction('generate');

        Queue::assertPushed(GeneratePayrollJob::class, function ($job) {
            return $job->periodStart === '2025-01-01' && $job->periodEnd === '2025-01-31';
        });
    }
}
