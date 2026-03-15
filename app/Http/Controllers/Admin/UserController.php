<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function approve(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $prestataire = $user->prestataire;
        if ($prestataire) {
            $prestataire->update(['is_validated' => 1]);
        }
        return back()->with('success', 'Compte approuvé.');
    }

    public function suspend(Request $request, $id)
    {
        return back()->with('info', 'Fonctionnalité à venir.');
    }

    public function destroy(Request $request, $id)
    {
        \App\Models\User::findOrFail($id)->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
}
