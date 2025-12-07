<?php

namespace App\Filament\Widgets;

use App\Models\JobReport;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ContributorStatsWidget extends ChartWidget
{
    protected ?string $heading = 'Top Contributors';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = JobReport::query()
            ->select('employee_id', DB::raw('count(*) as count'))
            ->where('status', JobReport::STATUS_COMPLETE)
            ->with('employee')
            ->groupBy('employee_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Completed Tasks',
                    'data' => $data->pluck('count')->toArray(),
                ],
            ],
            'labels' => $data->map(fn ($record) => $record->employee?->name ?? 'Unknown')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
