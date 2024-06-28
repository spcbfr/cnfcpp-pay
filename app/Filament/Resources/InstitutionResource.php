<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitutionResource\Pages;
use App\Filament\SuperAdmin\Resources\InstitutionResource\RelationManagers\CoursesRelationManager;
use App\InstitutionType;
use App\Models\Institution;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set as FilamentSet;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Collection\Set;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->columnSpan(2)
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('type')
                    ->options(InstitutionType::class)
                    ->live()
                    ->afterStateUpdated(fn (FilamentSet $set) => $set('majors', null))
                    ->required(),
                Select::make('majors')
                    ->relationship('majors', 'name',
                        modifyQueryUsing: function (Builder $query, Get $get) {
                            $query->where('type', $get('type'));
                        })
                    // ->options(fn (Get $get) => Major::where('type', '=', $get('type'))->get()->pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required(),
                Forms\Components\Select::make('state_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship(name: 'state', titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query->where('admin_id', auth()->id() )),
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
            ->modifyQueryUsing(function (Builder $query) {
                return $query->whereHas('state.admin', function (Builder $filter) {
                    $filter->where('id', auth()->id());
                });

            })
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
            CoursesRelationManager::class
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
