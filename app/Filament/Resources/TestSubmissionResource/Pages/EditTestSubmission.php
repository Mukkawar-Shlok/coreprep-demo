<?php

namespace App\Filament\Resources\TestSubmissionResource\Pages;

use App\Filament\Resources\TestSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestSubmission extends EditRecord
{
    protected static string $resource = TestSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
