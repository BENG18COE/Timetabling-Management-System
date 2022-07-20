<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicYearResource\Pages;
use App\Filament\Resources\AcademicYearResource\RelationManagers;
use App\Models\AcademicYear;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;
use pxlrbt\FilamentExcel\Actions\ExportAction;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $recordTitleAttribute = 'represent';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $default = "01/01/2016";
        return $form
            ->schema([
                Forms\Components\TextInput::make("represent")->required(),
                Forms\Components\DatePicker::make("start_year")->required()->default($default),
                Forms\Components\DatePicker::make("end_year")->required()->default($default),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('represent'),
                Tables\Columns\TextColumn::make('start_year')->date("d/m/Y"),
                Tables\Columns\TextColumn::make('end_year')->date("d/m/Y"),
            ])
            ->filters([
                //
            ]) 
            ->bulkActions([
                ExportAction::make('export')
                    ->color('success')
                    ->icon('heroicon-o-document')
                    ->except('password') // Exclude fields
                    ->withHeadings() // Get headings from table or form
                ,
                Tables\Actions\BulkAction::make('Delete')
                    ->action(fn (Collection $records) => $records->each->delete())
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademicYears::route('/'),
            'create' => Pages\CreateAcademicYear::route('/create'),
            'edit' => Pages\EditAcademicYear::route('/{record}/edit'),
        ];
    }
}
