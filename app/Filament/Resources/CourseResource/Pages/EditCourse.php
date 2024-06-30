<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    public function getSubheading(): string
    {
        return $this->record->institution->name;
    }

    protected $listeners = ['refreshCourse' => '$refresh'];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (DeleteAction $action, Course $record) {
                    if ($record->users()->count() !== 0) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to delete!')
                            ->body('Course has attached users.')
                            ->persistent()
                            ->send();

                        // This will halt and cancel the delete action modal.
                        $action->cancel();
                    }
                }),
        ];
    }
}
