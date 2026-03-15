<?php

namespace App\Http\Controllers;

use App\Models\Prestataire;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->clientReservations()
            ->with(['prestataire.user', 'service'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'accepte') {
                $query->whereIn('status', [Reservation::STATUS_ACCEPTE, Reservation::STATUS_EN_ROUTE]);
            } else {
                $query->where('status', $status);
            }
        }

        $reservations = $query->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prestataire_id'       => ['required', 'exists:prestataires,id'],
            'service_id'           => ['required', 'exists:services,id'],
            'type_demande'         => ['required', 'in:immediate,programmee'],
            'date_prevue'          => ['nullable', 'required_if:type_demande,programmee', 'date', 'after:now'],
            'adresse_intervention' => ['nullable', 'string', 'max:255'],
            'latitude'             => ['nullable', 'numeric'],
            'longitude'             => ['nullable', 'numeric'],
        ], [], [
            'prestataire_id'       => 'prestataire',
            'service_id'           => 'service',
            'date_prevue'          => 'date et heure',
            'adresse_intervention' => 'adresse',
        ]);

        $prestataire = Prestataire::findOrFail($validated['prestataire_id']);
        $service = $prestataire->services()->findOrFail($validated['service_id']);

        $clientId = auth()->user()->client?->id ?? auth()->id();
        if (! $clientId) {
            return back()->withErrors(['client' => 'Profil client introuvable.'])->withInput();
        }

        Reservation::create([
            'client_id'            => $clientId,
            'prestataire_id'       => $prestataire->id,
            'service_id'           => $service->id,
            'date_prevue'          => $validated['type_demande'] === 'programmee' ? $validated['date_prevue'] : null,
            'type_demande'         => $validated['type_demande'],
            'status'               => Reservation::STATUS_EN_ATTENTE,
            'adresse_intervention' => $validated['adresse_intervention'] ?? null,
            'latitude'             => $validated['latitude'] ?? null,
            'longitude'             => $validated['longitude'] ?? null,
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Demande envoyée. Vous serez notifié lorsque le prestataire répondra.');
    }

    public function cancel(Request $request, $id)
    {
        $reservation = auth()->user()->clientReservations()->findOrFail($id);

        if (! $reservation->canBeCancelled()) {
            return back()->with('error', 'Cette réservation ne peut plus être annulée.');
        }

        $reservation->update(['status' => Reservation::STATUS_ANNULE]);

        return back()->with('success', 'Réservation annulée.');
    }

    public function show($id)
    {
        $reservation = auth()->user()
            ->clientReservations()
            ->with(['prestataire.user', 'service', 'avis'])
            ->findOrFail($id);

        return view('reservations.show', compact('reservation'));
    }
}
