<?php

namespace App\Http\Controllers;

use App\Models\InvoiceRecord;
use App\Models\Payment;
use Barryvdh\DomPDF\PDF;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function printPaymentInvoice($id)
    {
        $payment = Payment::with(['reservation', 'reservation.room.boardingHouse', 'reservation.customer'])->find($id);
        if ($payment) {

            InvoiceRecord::create([
                'user_id' => $id,
                'payment_id' => $id

            ]);
            $pdf = \PDF::loadView('pdf.payment_invoice', compact('payment'));
            return $pdf->stream();
        } else {
            Notification::make()
                ->danger()
                ->title('No Payment Found Record')
                ->send();
            return redirect()->back();
        }
    }
}
