<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalEmployees = \App\Models\Employee::count();
        $monthlyPayroll = \App\Models\Payroll::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('net_salary');
        $activeProjects = \App\Models\Project::where('status', 'active')->count();

        return [
            Stat::make('Total Employees', $totalEmployees)
                ->description('Active workforce')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Monthly Payroll', 'IDR ' . number_format($monthlyPayroll, 0, ',', '.'))
                ->description('Current month cost')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
            Stat::make('Active Projects', $activeProjects)
                ->description('Ongoing projects')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),
        ];
    }
}
