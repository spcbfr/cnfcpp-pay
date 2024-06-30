<?php

namespace App\Filament\Resources\InstitutionResource\Pages;

use App\Filament\Resources\InstitutionResource;
use Filament\Actions;
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
            Actions\DeleteAction::make(),
        ];
    }
}
