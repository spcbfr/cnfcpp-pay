<?php

namespace App\Filament\Resources\InstitutionResource\Pages;

use App\Filament\Resources\InstitutionResource;
use App\Models\Institution;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditInstitution extends EditRecord
{
    protected static string $resource = InstitutionResource::class;

    public function getTitle(): string|Htmlable
    {
        //    return 'Edit Document ' . $this->record->id;
        return 'Modifier '.$this->record->name;
    }

    protected $listeners = ['refreshInstitution' => '$refresh'];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (DeleteAction $action, Institution $record) {
                    if ($record->courses()->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to delete!')
                            ->body('Institution has courses')
                            ->persistent()
                            ->send();

                        // This will halt and cancel the delete action modal.
                        $action->cancel();
                    }
                }),
        ];
    }
}
