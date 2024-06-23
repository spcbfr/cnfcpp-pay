<?php

namespace App\Filament\SuperAdmin\Resources\StateResource\Pages;

use App\Filament\SuperAdmin\Resources\StateResource;
use Filament\Resources\Pages\ListRecords;

class ListStates extends ListRecords
{
    protected static string $resource = StateResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
