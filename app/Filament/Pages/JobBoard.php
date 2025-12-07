<?php

namespace App\Filament\Pages;

use App\Models\JobReport;
use App\Models\Task;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Jessedev\FilamentKanban\Pages\KanbanBoard;
use Filament\Notifications\Notification;

class JobBoard extends KanbanBoard
{
    protected static string $model = JobReport::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | \UnitEnum | null $navigationGroup = 'Work Management';
    protected static ?string $title = 'Job Reports';

    protected function statuses(): \Illuminate\Support\Collection
    {
        return collect([
            [
                'id' => JobReport::STATUS_WAITING,
                'title' => 'Waiting',
            ],
            [
                'id' => JobReport::STATUS_TODO,
                'title' => 'To Do',
            ],
            [
                'id' => JobReport::STATUS_ON_PROGRESS,
                'title' => 'On Progress',
            ],
            [
                'id' => JobReport::STATUS_COMPLETE,
                'title' => 'Complete',
            ],
        ]);
    }

    protected function records(): \Illuminate\Support\Collection
    {
        return JobReport::ordered()->get();
    }

    public function onStatusChanged(int|string $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        JobReport::find($recordId)->update(['status' => $status]);
        JobReport::setNewOrder($toOrderedIds);

        Notification::make()
            ->title('Status updated')
            ->success()
            ->send();
    }

    public function onSortChanged(int|string $recordId, string $status, array $orderedIds): void
    {
        JobReport::setNewOrder($orderedIds);

        Notification::make()
            ->title('Order updated')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->model(JobReport::class)
                ->form([
                    Select::make('task_id')
                        ->label('Task')
                        ->options(Task::all()->pluck('name', 'id'))
                        ->required()
                        ->searchable(),
                    TextInput::make('title')
                        ->required(),
                    Textarea::make('description'),
                    Select::make('employee_id')
                        ->relationship('employee', 'name')
                        ->label('Employee')
                        ->searchable(),
                    Select::make('status')
                        ->options([
                            JobReport::STATUS_WAITING => 'Waiting',
                            JobReport::STATUS_TODO => 'To Do',
                            JobReport::STATUS_ON_PROGRESS => 'On Progress',
                            JobReport::STATUS_COMPLETE => 'Complete',
                        ])
                        ->default(JobReport::STATUS_WAITING)
                        ->required(),
                ])
                ->successNotificationTitle('Job Report created successfully'),
        ];
    }
}
