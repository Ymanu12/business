<?php

namespace App\Filament\Resources\LiveResource\Pages;

use App\Filament\Resources\LiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLive extends EditRecord
{
    protected static string $resource = LiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
