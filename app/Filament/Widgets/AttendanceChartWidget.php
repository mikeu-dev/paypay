<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class AttendanceChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;


    protected ?string $heading = 'Attendance Trends (Last 7 Days)';

    protected function getData(): array
    {
        $data = \App\Models\Attendance::selectRaw('date, status, count(*) as count')
            ->where('date', '>=', now()->subDays(7))
            ->groupBy('date', 'status')
            ->get();

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $dates->map(fn ($date) => $data->where('date', $date)->where('status', 'present')->first()->count ?? 0),
                    'backgroundColor' => '#22c55e', // Green
                ],
                [
                    'label' => 'Late',
                    'data' => $dates->map(fn ($date) => $data->where('date', $date)->where('status', 'late')->first()->count ?? 0),
                    'backgroundColor' => '#eab308', // Yellow
                ],
                [
                    'label' => 'Absent',
                    'data' => $dates->map(fn ($date) => $data->where('date', $date)->where('status', 'absent')->first()->count ?? 0),
                    'backgroundColor' => '#ef4444', // Red
                ],
            ],
            'labels' => $dates->map(fn ($date) => \Carbon\Carbon::parse($date)->format('M d')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
