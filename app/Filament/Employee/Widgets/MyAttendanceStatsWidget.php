<?php

namespace App\Filament\Employee\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Attendance;

class MyAttendanceStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return [
                Stat::make('Status', 'No Profile Linked')
                    ->description('Please contact HR')
                    ->color('danger'),
            ];
        }

        $present = Attendance::where('employee_id', $employee->id)->where('status', 'present')->count();
        $late = Attendance::where('employee_id', $employee->id)->where('status', 'late')->count();
        $absent = Attendance::where('employee_id', $employee->id)->where('status', 'absent')->count();

        return [
            Stat::make('Days Present', $present)
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Days Late', $late)
                ->color('warning')
                ->icon('heroicon-o-clock'),
            Stat::make('Days Absent', $absent)
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
        ];
    }
}
