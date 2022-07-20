<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use App\Imports\DepartmentImport;

use Filament\Forms\Components\FileUpload;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('New Department')->url($this->getResource()::getUrl("create")),
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
        Excel::import(new DepartmentImport, storage_path('app/public/'. $data['attachment'])) instanceof ExcelExcel ?
        $this->notify('success', 'Saved, All Rows Imported') :
        $this->notify('danger', 'Failed, No Rows Imported');
    }
}
