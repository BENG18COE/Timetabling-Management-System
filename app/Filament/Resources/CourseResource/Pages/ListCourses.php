<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Imports\CoursesImport;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Excel as ExcelExcel;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getActions(): array
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
        Excel::import(new CoursesImport, storage_path('app/public/'. $data['attachment']))
        instanceof ExcelExcel ?
        $this->notify('success', 'Saved, All Rows Imported') :
        $this->notify('error', 'Not all Rows Imported, Exists');
    }
}
