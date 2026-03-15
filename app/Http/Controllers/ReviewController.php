<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => ['required', 'exists:reservations,id'],
            'note'            => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire'     => ['nullable', 'string', 'max:1000'],
        ], [], [
            'reservation_id' => 'réservation',
            'note'            => 'note',
            'commentaire'    => 'commentaire',
        ]);

        $reservation = auth()->user()->clientReservations()->with('avis')->findOrFail($validated['reservation_id']);

        if (! $reservation->canBeReviewed()) {
            return back()->with('error', 'Cette réservation ne peut pas être évaluée.');
        }

        $clientId = auth()->user()->client?->id ?? auth()->id();

        Avis::create([
            'reservation_id' => $reservation->id,
            'client_id'      => $clientId,
            'prestataire_id' => $reservation->prestataire_id,
            'note'           => $validated['note'],
            'commentaire'    => $validated['commentaire'] ?? null,
            'date_avis'      => now(),
        ]);

        return back()->with('success', 'Merci pour votre évaluation.');
    }
}
