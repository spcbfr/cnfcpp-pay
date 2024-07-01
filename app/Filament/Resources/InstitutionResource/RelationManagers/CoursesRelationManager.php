<?php

namespace App\Filament\Resources\InstitutionResource\RelationManagers;

use App\InstitutionType;
use App\Models\Course;
use App\Models\Major;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    protected static ?string $modelLabel = 'Session';

    protected static ?string $title = 'Sessions';

    public function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Select::make('type')
                    ->unique(modifyRuleUsing: function (Unique $rule, Get $get, RelationManager $livewire) {
                        return $rule
                            ->where('major_id', $get('major_id'))
                            ->where('cycle', $get('cycle'))
                            ->where('promo', $get('promo'))
                            ->where('institution_id', $livewire->ownerRecord->id);
                    })
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
                    ->label('Select Specialite')
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
                Forms\Components\Radio::make('cycle')
                    ->translateLabel()->required()
                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'based on the № semesters of the chosen major')
                    ->options(function (?Model $record, Get $get) {

                        $fullYears = collect(['S1-S2', 'S3-S4', 'S5-S6', 'S7-S8', 'S9-S10'])->mapWithKeys(function ($item) {
                            return [$item => $item];
                        });

                        if (! is_null($get('major_id'))) {
                            $nbr = Major::find($get('major_id'))->number_of_semesters;

                            $returned = $fullYears->take(intdiv($nbr, 2));
                            if ($nbr % 2 === 1) {

                                $returned->put('S'.strval($nbr), 'S'.strval($nbr));
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
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('major.name')->label('Specialite')->description(fn (Course $record) => 'Promo '.$record->promo),
                Tables\Columns\TextColumn::make('year')->description(fn (Course $record) => 'Semester '.$record->semester)->label('Demarage De Session'),
                Tables\Columns\TextColumn::make('cycle')->label('Duree'),
                Tables\Columns\TextColumn::make('cost')->suffix('TND'),
            ])->striped()
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (Livewire $livewire) {
                        $livewire->dispatch('refreshInstitution');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (Livewire $livewire) {
                        $livewire->dispatch('refreshInstitution');
                    }),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Livewire $livewire) {
                        $livewire->dispatch('refreshInstitution');
                    }),

                Action::make('details')
                    ->label('Navigate')
                    ->url(fn ($record): string => route('filament.admin.resources.courses.edit', ['record' => $record]))
                    ->icon('heroicon-s-eye')
                    ->color('info'),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(function (Livewire $livewire) {
                        $livewire->dispatch('refreshInstitution');
                    }),
            ]);
    }
}
