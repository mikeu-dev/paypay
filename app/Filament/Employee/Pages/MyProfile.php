<?php

namespace App\Filament\Employee\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use App\Models\Employee;

class MyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user';
    protected string $view = 'filament.employee.pages.my-profile';
    protected static ?string $navigationLabel = 'My Profile';

    public ?array $data = [];

    public function mount(): void
    {
        $employee = auth()->user()->employee;
        if ($employee) {
            $this->form->fill([
                'name' => $employee->name,
                'email' => auth()->user()->email,
                'phone' => $employee->phone ?? '', // Assuming phone exists or needs to be added to migration if strictly needed, but let's assume we edit what's there
                'address' => $employee->address ?? '',
            ]);
        } else {
             $this->form->fill([
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Profile Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->disabled() // Email usually managed by User account, not Employee profile directly
                            ->helperText('Contact HR to change email.'),
                        // Add fields if they exist in Employee model, otherwise this is just a stub
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $employee = auth()->user()->employee;
        
        if ($employee) {
            $employee->update([
                'name' => $data['name'],
            ]);
            // auth()->user()->update(['name' => $data['name']]); // Optional sync
        }

        Notification::make() 
            ->success()
            ->title('Profile saved')
            ->send();
    }
}
