<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\InstitutionResource\Pages;
use App\Filament\SuperAdmin\Resources\InstitutionResource\RelationManagers\CoursesRelationManager;
use App\InstitutionType;
use App\Models\Institution;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->columnSpan(2)
                    ->unique(ignoreRecord: true)
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(InstitutionType::class)
                    ->required(),

                Forms\Components\Select::make('state_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship(name: 'state', titleAttribute: 'name'),
                Forms\Components\Fieldset::make('Manager')
                    ->schema([
                        Forms\Components\TextInput::make('manager_name')
                            ->required(),
                        Forms\Components\TextInput::make('manager_tel')
                            ->numeric()
                            ->length(8)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager_tel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstitutions::route('/'),
            'create' => Pages\CreateInstitution::route('/create'),
            'edit' => Pages\EditInstitution::route('/{record}/edit'),
        ];
    }
}
