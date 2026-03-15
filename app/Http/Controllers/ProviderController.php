<?php

namespace App\Http\Controllers;

use App\Models\Prestataire;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function show(Request $request, $id)
    {
        $prestataire = Prestataire::with('user', 'services.category')->findOrFail($id);
        $categoryId = $request->get('category');

        return view('providers.show', [
            'prestataire' => $prestataire,
            'categoryId'  => $categoryId,
        ]);
    }
}
