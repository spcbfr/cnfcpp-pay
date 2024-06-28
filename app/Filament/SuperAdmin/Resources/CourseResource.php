<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\CourseResource\Pages;
use App\InstitutionType;
use App\Models\Course;
use App\Models\Major;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $modelLabel = 'Session';

    protected static ?string $navigationBadgeTooltip = 'The number of sessions';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Select::make('type')
                    ->native(false)
                    ->options(function (?Model $record): array {

                        $both = [
                            'FMT' => 'Formation Modulaire Technique',
                            'FMG' => 'Formation Modulaire Gestion',
                        ];
                        if ($record->institution->type === InstitutionType::FU) {
                            return [
                                'LICENCE' => 'Licence',
                                'MASTER' => 'Master',
                                ...$both,

                            ];
                        } else {
                            return [
                                'CC' => 'CC',
                                'CAP' => 'CAP',
                                'BTP' => 'BTP',
                                'BTS' => 'BTS',
                                ...$both,

                            ];
                        }
                    })
                    ->required(),
                Forms\Components\Select::make('major_id')
                    ->label('Select Major')
                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'These are the majors available in your institution')
                    ->live()
                    ->native(false)
                    ->options(function (?Model $record): array {
                        return $record->institution->majors->pluck('name', 'id')->toArray();
                    }),

                Forms\Components\TextInput::make('promo')
                    ->prefix('Pr')
                    ->label('Promotion')
                    ->numeric()
                    ->required(),
                Forms\Components\Radio::make('cycle')
                    ->translateLabel()
                    ->required()
                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'based on the № semesters of the chosen major')
                    ->options(function (?Model $record, Get $get) {
                        $fullYears = collect(['S1-S2', 'S3-S4', 'S5-S6', 'S7-S8', 'S9-10']);
                        $halfYears = collect(['S1', 'S2', 'S3', 'S4', 'S5', 'S6', 'S7']);

                        $nbr = Major::find($get('major_id'))->number_of_semesters;

                        $returned = $fullYears->take(intdiv($nbr, 2));
                        if ($nbr % 2 === 1) {
                            $returned->push($halfYears[$nbr - 1]);
                        }

                        return $returned;

                    }),
                Forms\Components\Radio::make('semester')
                    ->label('début de la formation')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('year')
                    ->default(now()->year)
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->suffix('TND'),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type'),
                TextColumn::make('major.name'),

                Tables\Columns\TextColumn::make('cost')
                    ->money('TND')
                    ->sortable(),
                Tables\Columns\TextColumn::make('institution.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('year')
                    ->numeric(thousandsSeparator: '')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->prefix('Semester ')
                    ->numeric(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
        ];
    }
}
