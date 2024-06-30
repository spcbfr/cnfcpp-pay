<?php

namespace App\Filament\Imports;

use App\Models\User;
use Carbon\CarbonInterface;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('first_name')
                ->requiredMapping()
                ->guess(['prenom'])
                ->rules(['required', 'max:255']),
            ImportColumn::make('last_name')
                ->requiredMapping()
                ->guess(['nom'])
                ->rules(['required', 'max:255']),
            ImportColumn::make('cin')
                ->label('CIN')
                ->requiredMapping()
                ->rules(['required', 'numeric', 'digits:8']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['email']),
            ImportColumn::make('password')
                ->requiredMapping()
                ->guess(['mot de passe', 'pass'])
                ->rules(['required']),
            ImportColumn::make('gsm')
                ->label('GSM')
                ->requiredMapping()
                ->guess(['tel', 'phone', 'gsm'])
                ->rules(['required', 'numeric', 'digits:8']),
        ];
    }

    public function resolveRecord(): ?User
    {
        // return User::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new User();
    }

    public function getJobRetryUntil(): ?CarbonInterface
    {
        return now()->addMinute();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
