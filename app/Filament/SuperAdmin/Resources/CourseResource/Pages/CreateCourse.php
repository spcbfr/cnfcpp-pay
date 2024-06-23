<?php

namespace App\Filament\SuperAdmin\Resources\CourseResource\Pages;

use App\Filament\SuperAdmin\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
}
