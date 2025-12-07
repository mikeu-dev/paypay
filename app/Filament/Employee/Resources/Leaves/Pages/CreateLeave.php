<?php

namespace App\Filament\Employee\Resources\Leaves\Pages;

use App\Filament\Employee\Resources\Leaves\LeaveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['employee_id'] = auth()->user()->employee->id;
        $data['status'] = 'pending';
        return $data;
    }
}
