<?php

namespace App\Filament\Resources\Pages\Concerns;

use Filament\Actions\Action;
use Livewire\Attributes\Locked;

trait HasResourceIndexNavigation
{
    #[Locked]
    public ?string $initialFormStateHash = null;

    protected function afterFill(): void
    {
        $this->initialFormStateHash = $this->getFormStateHash();
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getBackToIndexAction(): Action
    {
        return Action::make('backToIndex')
            ->label('Kembali')
            ->icon('heroicon-o-arrow-left')
            ->color('gray')
            ->action(fn () => $this->redirect(static::getResource()::getUrl('index')))
            ->requiresConfirmation(fn (): bool => $this->hasUnsavedFormChanges())
            ->modalHeading(fn (): ?string => $this->hasUnsavedFormChanges()
                ? 'Perubahan belum disimpan. Yakin ingin kembali?'
                : null)
            ->modalCancelActionLabel('Tetap di sini')
            ->modalSubmitActionLabel('Kembali tanpa menyimpan');
    }

    protected function hasUnsavedFormChanges(): bool
    {
        return $this->initialFormStateHash !== $this->getFormStateHash();
    }

    protected function getFormStateHash(): string
    {
        return md5(json_encode($this->data ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '');
    }
}
