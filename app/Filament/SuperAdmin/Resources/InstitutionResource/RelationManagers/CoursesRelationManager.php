<?php

namespace App\Filament\SuperAdmin\Resources\InstitutionResource\RelationManagers;

use App\InstitutionType;
use App\Models\Major;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    public function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Select::make('type')
                    ->native(false)
                    ->options(function (RelationManager $livewire): array {
                        $both = [
                            'FMT' => 'Formation Modulaire Technique',
                            'FMG' => 'Formation Modulaire Gestion',
                        ];
                        if ($livewire->ownerRecord->type === InstitutionType::FU) {
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
                    ->live()
                    ->native(false)
                    ->options(function (RelationManager $livewire): array {
                        return $livewire->ownerRecord->majors->pluck('name', 'id')->toArray();
                    }),

                Forms\Components\TextInput::make('promo')
                    ->prefix('Pr')
                    ->label('Promotion')
                    ->numeric()
                    ->required(),
                Forms\Components\Radio::make('Cycle')
                    ->translateLabel()
                    ->required()
                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'based on the № semesters of the chosen major')
                    ->options(function (?Model $record, Get $get) {
                        $fullYears = collect(['S1-S2', 'S3-S4', 'S5-S6','S7-S8', 'S9-S10']);

                        if(!is_null($get('major_id'))) {
                            $nbr =  Major::find($get('major_id'))->number_of_semesters;

                            $returned = $fullYears->take(intdiv($nbr, 2));
                            if ($nbr % 2 === 1) {

                                $returned->push("S" . strval($nbr));
                            }

                            return $returned;

                        }


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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('major.name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextColumn::make('promo')->label('promotion')->prefix('Pr '),
                Tables\Columns\TextColumn::make('cost')->suffix('TND'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
