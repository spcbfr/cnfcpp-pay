<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\Course;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component as Livewire;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cin')
                    ->label('CIN')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('cin')
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->weight(FontWeight::SemiBold)
                    ->description(fn ($record) => $record->last_name),
                Tables\Columns\TextColumn::make('cin')
                    ->label('CIN'),
                Tables\Columns\TextColumn::make('email'),

                Tables\Columns\TextColumn::make('gsm')
                    ->label('GSM'),

                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid?')
                    ->trueIcon('heroicon-o-currency-dollar')
                    ->falseIcon('heroicon-o-currency-dollar')
                    ->falseColor('gray')
                    ->alignCenter()
                    ->boolean(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('Attach')
                    ->form([
                        TagsInput::make('ids')
                            ->label('IDs')
                            ->validationAttribute('IDs')
                            ->required()
                            ->rules([
                                fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                    $records = User::whereIn('cin', $value)->pluck('cin')->toArray();
                                    $nonExistantIds = array_diff($value, $records);

                                    if (! empty($nonExistantIds)) {
                                        $fail('The following :attribute don\'t exist in the the database: '.implode(', ', $nonExistantIds));
                                    }
                                },
                            ])
                            ->splitKeys([' ']),
                    ])->action(function (array $data, RelationManager $livewire) {

                        $ids = User::whereIn('cin', $data['ids'])->pluck('id')->toArray();

                        $course = Course::find($livewire->ownerRecord->id);

                        $course->users()->syncWithoutDetaching($ids);

                    }),
            ])
            ->actions([
                DetachAction::make()
                    ->before(function (DetachAction $action, ?Model $record) {
                        if ($record->is_paid) {

                            Notification::make()
                                ->danger()
                                ->title('Failed to detach!')
                                ->body('You can\'t detach a user that has paid the session fees')
                                ->persistent()
                                ->send();
                            $action->cancel();
                        }
                    })
                    ->after(function (Livewire $livewire) {
                        $livewire->dispatch('refreshCourse');
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\DetachBulkAction::make()
                //     ->after(function (Livewire $livewire) {
                //         $livewire->dispatch('refreshCourse');
                //     }),
            ]);
    }
}
