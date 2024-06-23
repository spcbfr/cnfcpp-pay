<?php

namespace App\Filament\SuperAdmin\Resources\InstitutionResource\RelationManagers;

use App\InstitutionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    public function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('cost')
                    ->prefix('TND'),
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
                Forms\Components\Select::make('type')
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
