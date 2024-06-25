<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum InstitutionType: string implements HasColor, HasIcon, HasLabel
{
    case FP = 'Formation Professionnelle';
    case FU = 'Formation Universitaire';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::FP => 'gray',
            self::FU => 'warning',

        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::FU => 'heroicon-m-book-open',
            self::FP => 'heroicon-s-wrench',

        };
    }
}
