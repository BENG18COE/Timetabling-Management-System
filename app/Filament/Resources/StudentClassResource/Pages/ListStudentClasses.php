<?php

namespace App\Filament\Resources\StudentClassResource\Pages;

use App\Filament\Resources\StudentClassResource;
use App\Imports\StudentClassImport;
use App\Models\StudentClass;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class ListStudentClasses extends ListRecords
{
    protected static string $resource = StudentClassResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('New Class')->url($this->getResource()::getUrl("create")),
            Action::make('Import')->action('openSettingsModal')
            ->color("success")
            ->icon('heroicon-o-document-text')
            ->form([
                FileUpload::make('attachment')->label("Excel File")
                ->preserveFilenames()->required(),
            ])
        ];
    }
    
    public function openSettingsModal(array $data)
    {
        Excel::import(new StudentClassImport, storage_path('app/public/'. $data['attachment'])) instanceof ExcelExcel ?
        $this->notify('success', 'Saved, All Rows Imported') : 
        $this->notify('danger', 'Failed, No Rows Imported');
    }
}
