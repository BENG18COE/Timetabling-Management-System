<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Imports\StudentImport;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('New Student')->url($this->getResource()::getUrl("create")),
            Action::make('Import')->action('openSettingsModal')
            ->color("success")->icon("heroicon-o-document-text")
            ->form([
                FileUpload::make('attachment')->label("Excel/CSV File")
                ->acceptedFileTypes([
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel',
                    'text/csv'])
                ->preserveFilenames()->required(),
            ]),
        ];
    }
    
    public function openSettingsModal(array $data)
    {
        Excel::import(new StudentImport, storage_path('app/public/'. $data['attachment'])) instanceof ExcelExcel ?
        $this->notify('success', 'Saved, All Rows Imported') :
        $this->notify('danger', 'Some Rows Imported, Other Exists');
    }
}
