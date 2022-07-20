<?php

namespace App\Filament\Resources\StudentClassResource\Pages;

use App\Filament\Resources\StudentClassResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentClass extends CreateRecord
{
    protected static string $resource = StudentClassResource::class;

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
