<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitutionResource\Pages;
use App\Filament\Resources\InstitutionResource\RelationManagers\CoursesRelationManager;
use App\InstitutionType;
use App\Models\Institution;
use App\Models\Major;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set as FilamentSet;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('type'),
                TextEntry::make('majors.name'),
                TextEntry::make('state.name'),
                Fieldset::make('Manager')
                    ->translateLabel()
                    ->schema([
                        TextEntry::make('manager_name'),
                        TextEntry::make('manager_tel'),
                    ]),

            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->columnSpan(2)
                    ->unique(ignoreRecord: true)
                    ->required(),
                ToggleButtons::make('type')
                    ->options(InstitutionType::class)
                    ->inline()
                    ->live()
                    ->afterStateUpdated(fn (FilamentSet $set) => $set('majors', null))
                    ->hintIcon(fn (Component $component) => $component->isDisabled() ? 'heroicon-m-question-mark-circle' : null)
                    ->hintColor('danger')
                    ->hintIconTooltip(fn (Component $component) => $component->isDisabled() ? __("You can't change an institution's type if it has sessions created") : null)
                    ->disabled(fn (?Model $record, string $operation) => $operation === 'create' ? false : $record->courses()->count() !== 0)
                    ->required(),
                Select::make('majors')
                    ->label('Spécialités')
                    ->disabled(fn (?Model $record, string $operation) => $operation === 'create' ? false : $record->courses()->count() !== 0)
                    ->hintIcon(fn (Component $component) => $component->isDisabled() ? 'heroicon-m-question-mark-circle' : null)
                    ->hintColor('danger')
                    ->hintIconTooltip(fn (Component $component) => $component->isDisabled() ? __("You can't change an institution's major if it has sessions created") : null)
                    ->relationship('majors', 'name',
                        modifyQueryUsing: function (Builder $query, Get $get) {
                            $query->where('type', $get('type'));
                        })
                    // ->options(fn (Get $get) => Major::where('type', '=', $get('type'))->get()->pluck('name', 'id')->toArray())
                    ->multiple()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('state_id')
                    ->label('Governorat')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship(
                        name: 'state',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => auth()->user()->isSuperAdmin() ? $query : $query->where('admin_id', auth()->id())),
                Forms\Components\Fieldset::make('Directeur')
                    ->schema([
                        Forms\Components\TextInput::make('manager_name')
                            ->label('Name')
                            ->required(),
                        Forms\Components\TextInput::make('manager_tel')
                            ->label('Phone number')
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
                if (auth()->user()->can('list only own institutions') && ! auth()->user()->isSuperAdmin()) {
                    return $query->whereHas('state.admin', function (Builder $filter) {
                        $filter->where('id', auth()->id());
                    });
                }
                if (auth()->user()->cannot('list only own institutions')) {
                    return $query;
                }

                return $query;

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
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewInstitution::route('/{record}'),
            'edit' => Pages\EditInstitution::route('/{record}/edit'),
        ];
    }
}
