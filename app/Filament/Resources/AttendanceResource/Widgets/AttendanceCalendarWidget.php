<?php

namespace App\Filament\Resources\AttendanceResource\Widgets;

use App\Models\Attendance;
use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\ValueObjects\FetchInfo;
use Guava\Calendar\Filament\CalendarWidget;
use Illuminate\Support\Collection;

class AttendanceCalendarWidget extends CalendarWidget
{
    public function getEvents(FetchInfo $fetchInfo): Collection|array
    {
        return Attendance::query()
            ->with('employee')
            ->whereBetween('date', [$fetchInfo->start, $fetchInfo->end])
            ->get()
            ->map(function (Attendance $attendance) {
                $color = match ($attendance->status) {
                    'present' => 'green',
                    'late' => 'yellow',
                    'absent' => 'red',
                    'permission' => 'blue',
                    default => 'gray',
                };

                return Event::make()
                    ->title("{$attendance->employee->name} ({$attendance->status})")
                    ->start($attendance->date . ' ' . ($attendance->start_time ?? '00:00:00'))
                    ->end($attendance->date . ' ' . ($attendance->end_time ?? '23:59:59'))
                    ->backgroundColor($color)
                    ->borderColor($color);
            });
    }
}
