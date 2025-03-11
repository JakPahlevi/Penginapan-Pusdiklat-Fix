<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Reservation;

class ReservationObserver
{
    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        // Ambil semua pembayaran yang berelasi dengan reservasi ini  
        $payments = Payment::where('reservation_id', $reservation->id)->get();

        // Update amount di setiap pembayaran  
        foreach ($payments as $payment) {
            $payment->amount = $reservation->total_amount; // Atau logika lain sesuai kebutuhan  
            $payment->save();
        }
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "restored" event.
     */
    public function restored(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "force deleted" event.
     */
    public function forceDeleted(Reservation $reservation): void
    {
        //
    }
}
