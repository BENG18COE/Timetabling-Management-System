<?php

namespace App\Filament\Resources\AcademicYearResource\Pages;

use App\Filament\Resources\AcademicYearResource;
use App\Imports\AcademicYearImport;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class ListAcademicYears extends ListRecords
{
    protected static string $resource = AcademicYearResource::class;

    public function getActions() : Array
    {
        return [
            Action::make('New Course')->url($this->getResource()::getUrl("create")),
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
        Excel::import(new AcademicYearImport, storage_path('app/public/'. $data['attachment']))
        instanceof ExcelExcel ?
        $this->notify('success', 'Saved, All Rows Imported') :
        $this->notify('error', 'Not all Rows Imported, Exists');
    }
}
