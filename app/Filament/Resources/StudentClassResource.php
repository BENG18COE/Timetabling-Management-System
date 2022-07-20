<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentClassResource\Pages;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Department;
use App\Models\StudentClass;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;

class StudentClassResource extends Resource
{
    protected static ?string $model = StudentClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 3;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")->required(),
                Forms\Components\Select::make("course_id")
                    ->label('course')
                    ->options(Course::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make("department_id")
                    ->label('Department')
                    ->options(Department::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make("academic_year_id")
                    ->label('academic year')
                    ->options(AcademicYear::all()->pluck('represent', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('department.name')->label('department'),
                Tables\Columns\TextColumn::make('course.name')->label('course'),
                Tables\Columns\TextColumn::make('academic_year.represent')->label('academic year'),
            ])
            ->filters([
                //
            ])
            ->bulkActions([
                ExportAction::make('export')
                    ->color('success')
                    ->icon('heroicon-o-document-text')
                    ->except('password') // Exclude fields
                    ->withHeadings() // Get headings from table or form
                    ->deselectRecordsAfterCompletion()
                ,
                Tables\Actions\BulkAction::make('Delete')
                    ->action(fn (Collection $records) => $records->each->delete())
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->deselectRecordsAfterCompletion(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentClasses::route('/'),
            'create' => Pages\CreateStudentClass::route('/create'),
            'edit' => Pages\EditStudentClass::route('/{record}/edit'),
            'view' => Pages\ViewClass::route('/{record}/view'),
        ];
    }
}
