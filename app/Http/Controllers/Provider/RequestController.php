<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function accept(Request $request, $id)
    {
        $reservation = auth()->user()->providerReservations()->findOrFail($id);
        $reservation->update(['status' => Reservation::STATUS_ACCEPTE]);
        return back()->with('success', 'Demande acceptée.');
    }

    public function refuse(Request $request, $id)
    {
        $reservation = auth()->user()->providerReservations()->findOrFail($id);
        $reservation->update(['status' => Reservation::STATUS_REFUSE]);
        return back()->with('success', 'Demande refusée.');
    }

    public function propose(Request $request, $id)
    {
        return back()->with('info', 'Fonctionnalité à venir.');
    }
}
