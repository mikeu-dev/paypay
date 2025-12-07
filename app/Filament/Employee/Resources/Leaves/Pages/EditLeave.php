<?php

namespace App\Filament\Employee\Resources\Leaves\Pages;

use App\Filament\Employee\Resources\Leaves\LeaveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeave extends EditRecord
{
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
