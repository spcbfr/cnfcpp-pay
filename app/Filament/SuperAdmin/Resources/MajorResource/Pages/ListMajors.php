<?php

namespace App\Filament\SuperAdmin\Resources\MajorResource\Pages;

use App\Filament\SuperAdmin\Resources\MajorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMajors extends ListRecords
{
    protected static string $resource = MajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
