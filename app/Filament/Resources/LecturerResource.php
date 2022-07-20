<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LecturerResource\Pages;
use App\Filament\Resources\LecturerResource\RelationManagers;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use App\Models\Lecturer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;

class LecturerResource extends Resource
{
    protected static ?string $model = Lecturer::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Personnel';
    
    public static function form(Form $form): Form
    {
        $uuid = rand(1000000,9999999);
        return $form
            ->schema([
                Forms\Components\TextInput::make("uuid")->required()->default($uuid),
                Forms\Components\TextInput::make("name")->required(),
                Forms\Components\TextInput::make('email')->email()->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('password')->password()->required()
                    ->default($uuid)
                    ->hiddenOn("Pages\EditUser")
                    ->same('passwordConfirmation')
                    ->maxLength(255),
                Forms\Components\TextInput::make('passwordConfirmation')->password()->required()
                    ->default($uuid)
                    ->hiddenOn("Pages\EditUser")
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label("Email Verified"),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ListLecturers::route('/'),
            'create' => Pages\CreateLecturer::route('/create'),
            'edit' => Pages\EditLecturer::route('/{record}/edit'),
            'view' => Pages\ViewLecturer::route('/{record}/view'),
        ];
    }
}
