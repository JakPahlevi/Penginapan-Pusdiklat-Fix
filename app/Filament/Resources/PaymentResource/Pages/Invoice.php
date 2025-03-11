<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Psy\VersionUpdater\Downloader\FileDownloader;

class Invoice extends Page
{
    protected static string $resource = PaymentResource::class;

    public $record;
    public $payment;

    public function mount($record)
    {
        //['reservation'] data relasion yg akan di tampilkan
        $this->record = $record;
        $this->payment = Payment::with(['reservation', 'reservation.room.boardingHouse', 'reservation.customer'])->find($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->icon('heroicon-s-printer')
                ->requiresConfirmation()
                ->url(route('PRINT.PAYMENT_INVOICE', ['id' => $this->record])),

        ];
    }

    protected static string $view = 'filament.resources.payment-resource.pages.invoice';
}
