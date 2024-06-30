<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers\InstitutionsRelationManager;
use App\InstitutionType;
use App\Models\State;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Parametres';

    protected static ?string $modelLabel = 'Governorats';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->disabled()
                    ->required(),
                Select::make('admin_id')
                    ->relationship('admin', 'region_name'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount(
                [
                    'institutions as fp' => fn (Builder $query) => $query->where('type', InstitutionType::FP),
                    'institutions as fu' => fn (Builder $query) => $query->where('type', InstitutionType::FU),
                ]
            )
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Gouvernorat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fu')->label('№ Institution Universitaire')->summarize(Sum::make()),
                Tables\Columns\TextColumn::make('fp')->label('№ Institution Professionnelle')->summarize(Sum::make()),
                TextColumn::make('admin.region_name')
                    ->label('IPST/UR')
                    ->searchable()
                    ->sortable(),
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

    public static function getRelations(): array
    {
        return [
            InstitutionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStates::route('/'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
