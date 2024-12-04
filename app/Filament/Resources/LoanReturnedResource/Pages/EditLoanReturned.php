<?php

namespace App\Filament\Resources\LoanReturnedResource\Pages;

use App\Filament\Resources\LoanReturnedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoanReturned extends EditRecord
{
    protected static string $resource = LoanReturnedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
