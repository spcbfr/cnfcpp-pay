<?php

namespace App\Filament\Resources\StateResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;

class InstitutionsRelationManager extends RelationManager
{
    protected static string $relationship = 'Institutions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('manager_name'),
                Tables\Columns\TextColumn::make('manager_tel')->copyable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([

                Action::make('details')
                    ->label('Navigate')
                    ->url(fn ($record): string => route('filament.superAdmin.resources.institutions.edit', ['record' => $record]))
                    ->icon('heroicon-s-eye')
                    ->color('info'),
            ])
            ->bulkActions([]);
    }
}
