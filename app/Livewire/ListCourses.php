<?php

namespace App\Livewire;

use App\Models\Course;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Livewire\Component;

class ListCourses extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {

        $courseIds = auth()->user()->courses()->pluck('courses.id');
        $query = Course::query()->whereIn('id', $courseIds);
        $relationship = auth()->user()->courses();

        return $table
            ->relationship(fn (): BelongsToMany => $relationship)
            ->inverseRelationship('courses')
            ->query(
                $query
            )
            ->columns([
                TextColumn::make('type'),
                TextColumn::make('major.name'),
                TextColumn::make('cost')
                    ->label('A payer'),
                TextColumn::make('cost')
                    ->money('TND')
                    ->sortable(),
                TextColumn::make('institution.name'),
                IconColumn::make('is_paid')
                    ->boolean(),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render(): View
    {
        return view('livewire.list-courses');
    }
}
