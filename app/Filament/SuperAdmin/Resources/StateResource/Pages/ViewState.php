<?php

namespace App\Filament\SuperAdmin\Resources\StateResource\Pages;

use App\Filament\SuperAdmin\Resources\StateResource;
use Filament\Resources\Pages\ViewRecord;

class ViewState extends ViewRecord
{
    protected static string $resource = StateResource::class;

    protected static ?string $title = 'Custom Page Title';

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
