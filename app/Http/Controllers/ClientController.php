<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Prestataire;
use App\Models\Reservation;
use Illuminate\Http\Request;

/**
 * Contrôleur de l'interface client (layout client, couleurs #FF6B35).
 * Le dashboard est côté admin ; ici : Accueil, Recherche, Réservations, Messagerie, Profil.
 */
class ClientController extends Controller
{
    /** Accueil client (pas un dashboard admin) */
    public function home()
    {
        $user = auth()->user();
        $reservations = $user->clientReservations()->with('prestataire.user')->orderByDesc('created_at')->limit(5)->get();
        $pending = $user->clientReservations()->where('status', Reservation::STATUS_EN_ATTENTE)->count();
        $inProgress = $user->clientReservations()->whereIn('status', [Reservation::STATUS_ACCEPTE, Reservation::STATUS_EN_ROUTE])->count();
        $completed = $user->clientReservations()->where('status', Reservation::STATUS_TERMINE)->count();

        return view('client.home', [
            'reservations' => $reservations,
            'pending'      => $pending,
            'inProgress'   => $inProgress,
            'completed'    => $completed,
        ]);
    }

    /** Recherche de prestataires / réserver un service */
    public function search(Request $request)
    {
        $categoryId = $request->get('category');
        $q = $request->get('q');
        $type = $request->get('type', 'immediate');

        $prestataires = Prestataire::with('user', 'services')
            ->when($categoryId, fn ($query) => $query->whereHas('services', fn ($q) => $q->where('category_id', $categoryId)))
            ->when($q, fn ($query) => $query->whereHas('user', fn ($subQuery) => $subQuery->where('name', 'like', '%' . $q . '%')))
            ->orderBy('id')
            ->limit(50)
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('client.search', [
            'categories'   => $categories,
            'prestataires' => $prestataires,
            'q'            => $q,
            'category'     => $categoryId,
            'type'         => $type,
        ]);
    }

    /** Liste des réservations (historique) */
    public function historique(Request $request)
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

        return view('client.reservations.index', compact('reservations'));
    }

    /** Formulaire nouvelle réservation (prestataire présélectionné) */
    public function createReservation(Request $request)
    {
        $prestataireId = $request->get('provider') ?? $request->get('prestataire');
        $prestataire = $prestataireId
            ? Prestataire::with('user', 'services')->find($prestataireId)
            : null;

        return view('client.reservations.create', ['prestataire' => $prestataire]);
    }

    /** Détail d'une réservation */
    public function showReservation($id)
    {
        $reservation = auth()->user()
            ->clientReservations()
            ->with(['prestataire.user', 'service', 'avis'])
            ->findOrFail($id);

        return view('client.reservations.show', compact('reservation'));
    }

    /** Messagerie (placeholder) */
    public function messagerie()
    {
        return view('client.messagerie');
    }

    /** Profil client */
    public function profil()
    {
        return view('client.profil');
    }
}
