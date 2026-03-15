<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function update(Request $request)
    {
        $prestataire = auth()->user()->prestataire;
        if (! $prestataire) {
            return back()->with('error', 'Profil prestataire introuvable.');
        }
        $status = $request->input('availability', 'disponible');
        if (in_array($status, ['disponible', 'indisponible'], true)) {
            $prestataire->update(['availability' => $status]);
        }
        return back()->with('success', 'Disponibilité mise à jour.');
    }
}
