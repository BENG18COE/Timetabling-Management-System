<?php

namespace App\Filament\Resources\LecturerResource\Pages;

use App\Filament\Resources\LecturerResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateLecturer extends CreateRecord
{
    protected static string $resource = LecturerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data["passwordConfirmation"]);
        return $data;
    }

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
