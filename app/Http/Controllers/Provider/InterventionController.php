<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $reservation = auth()->user()->providerReservations()->findOrFail($id);
        $status = $request->input('status');
        if (in_array($status, [Reservation::STATUS_EN_ROUTE, Reservation::STATUS_TERMINE], true)) {
            $reservation->update(['status' => $status]);
        }
        return back()->with('success', 'Statut mis à jour.');
    }
}
