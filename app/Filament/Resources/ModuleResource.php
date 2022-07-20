<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Filament\Resources\ModuleResource\RelationManagers;
use App\Models\Department;
use App\Models\Lecturer;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use App\Models\Module;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;
    protected static ?string $recordTitleAttribute = "code";
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")->required(),
                Forms\Components\TextInput::make("code")->required(),
                Forms\Components\Select::make("type")->searchable()->required()
                ->options([
                    "core" => "Core",
                    "fundamental" => "Fundamental",
                    "elective" => "Elective",
                ])->default('core'),
                Forms\Components\Select::make("department_id")->required()->label("Department")
                ->options(Department::all()->pluck('name','id')),
                Forms\Components\Select::make("lecturer_id")->required()->label("Lecturer")
            ->options(Lecturer::all()->pluck('name','id')),
                Forms\Components\TextInput::make("credits")->numeric()->required()->default(2),
                Forms\Components\TextInput::make("capacity")->numeric()->required()->default(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name"),
                Tables\Columns\TextColumn::make("code"),
                Tables\Columns\TextColumn::make("credits"),
                Tables\Columns\TextColumn::make("capacity"),
                Tables\Columns\TextColumn::make("type")->sortable(),
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
            'index' => Pages\ListModules::route('/'),
            'create' => Pages\CreateModule::route('/create'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
            'view' => Pages\ViewModule::route('/{record}/view'),
        ];
    }
}
