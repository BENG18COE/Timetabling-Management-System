<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use GrahamCampbell\ResultType\Success;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

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
