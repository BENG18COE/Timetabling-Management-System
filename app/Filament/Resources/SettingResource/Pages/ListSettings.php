<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Imports\SettingImport;
use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('New Setting')->url($this->getResource()::getUrl("create")),
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
        Excel::import(new SettingImport, storage_path('app/public/'. $data['attachment'])) instanceof Setting ?
        $this->notify('success', 'Saved, All Rows Imported') : $this->notify('danger', 'Failed, No Rows Imported');
    }
}
