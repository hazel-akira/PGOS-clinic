<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;

class SystemSettings extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'System';
    protected static string $view = 'filament.admin.pages.system-settings';

    public function mount(): void
    {
        $this->form->fill(
            Setting::pluck('value', 'key')->toArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->notify('success', 'Settings saved successfully.');
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('clinic_name')
                ->required(),

            Forms\Components\TextInput::make('low_stock_threshold')
                ->numeric(),

            Forms\Components\TextInput::make('support_email')
                ->email(),
        ];
    }
}
