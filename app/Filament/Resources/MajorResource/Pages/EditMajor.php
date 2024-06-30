<?php

namespace App\Filament\Resources\MajorResource\Pages;

use App\Filament\Resources\MajorResource;
use App\Models\Major;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMajor extends EditRecord
{
    protected static string $resource = MajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (DeleteAction $action, Major $record) {
                    if ($record->institutions()->count() !== 0) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to delete!')
                            ->body('Major is assigned to institutions')
                            ->persistent()
                            ->send();

                        // This will halt and cancel the delete action modal.
                        $action->cancel();
                    }
                }),
        ];
    }
}
