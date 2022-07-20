<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use App\Models\Course;
use App\Models\Department;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")->required(),
                Forms\Components\Select::make("department_id")->required()->label("Department")
                ->options(Department::all()->pluck('name','id')),
                Forms\Components\TextInput::make("code")->required(),
                Forms\Components\Select::make("program")->required()
                ->options([
                    "Ordinary Diploma" => "Ordinary Diploma",
                    "Bachelor Degree" => "Bachelor Degree",
                    "Masters Degree" => "Masters Degree",
                ]),
                Forms\Components\Select::make("field")->required()
                ->options([
                    "Engineering" => "Engineering",
                    "Technical" => "Technical",
                    "Science" => "Science",
                ]), 
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('program'),
                Tables\Columns\TextColumn::make('department.name'),
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
                    ->requiresConfirmation("Export Selected to Excel")
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
            'view' => Pages\ViewCourse::route('/{record}/view'),
        ];
    }

}
