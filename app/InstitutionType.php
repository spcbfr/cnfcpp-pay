<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum InstitutionType: string implements HasLabel
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
            self::FU => 'blue',

        };
    }
}
