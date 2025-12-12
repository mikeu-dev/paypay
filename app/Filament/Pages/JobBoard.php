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

use Filament\Actions\Action;
use Livewire\Attributes\Url;

class JobBoard extends KanbanBoard
{
    protected static string $model = JobReport::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | \UnitEnum | null $navigationGroup = 'Work Management';
    protected static ?string $title = 'Job Reports';

    #[Url]
    public ?string $taskId = null;

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
        if (! $this->taskId) {
            return collect();
        }

        return JobReport::ordered()
            ->where('task_id', $this->taskId)
            ->get();
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
            Action::make('filter')
                ->label('Filter by Task')
                ->icon('heroicon-m-funnel')
                ->form([
                    Select::make('taskId')
                        ->label('Task')
                        ->options(Task::all()->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->default($this->taskId),
                ])
                ->action(function (array $data) {
                    $this->taskId = $data['taskId'];
                }),
        ];
    }
}
