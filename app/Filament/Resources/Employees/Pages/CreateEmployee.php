<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        ]);

        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Employee']);
        $user->assignRole($role);

        $data['user_id'] = $user->id;

        unset($data['email']);
        unset($data['password']);

        return $data;
    }
}
