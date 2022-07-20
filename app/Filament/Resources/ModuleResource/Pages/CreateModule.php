<?php

namespace App\Filament\Resources\ModuleResource\Pages;

use App\Filament\Resources\ModuleResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Save')
                ->action(fn() => $this->create())
                ->keyBindings(['command+s', 'ctrl+s']),
            Action::make('Cancel')->url($this->getResource()::getUrl('index'))
                ->color("secondary")
                ->keyBindings(['command+shift+c', 'ctrl+shift+c']),
        ];
    }
}
