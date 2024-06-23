<?php

namespace App\Filament\SuperAdmin\Resources\InstitutionResource\Pages;

use App\Filament\SuperAdmin\Resources\InstitutionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInstitution extends CreateRecord
{
    protected static string $resource = InstitutionResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
