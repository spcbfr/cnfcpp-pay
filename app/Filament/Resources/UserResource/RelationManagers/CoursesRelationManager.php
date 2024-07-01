<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('major.name'),

                Tables\Columns\TextColumn::make('cost')
                    ->money('TND')
                    ->sortable(),
                Tables\Columns\TextColumn::make('institution.name'),
                Tables\Columns\TextColumn::make('year')
                    ->numeric(thousandsSeparator: '')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->prefix('Semester ')
                    ->numeric(),
                Tables\Columns\IconColumn::make('is_paid')->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }
}
