<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Prestataire;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->filled('category') ? (int) $request->get('category') : null;
        $q = $request->get('q');
        $type = $request->get('type', 'immediate');

        // Charger uniquement les services de la catégorie sélectionnée pour éviter tout chevauchement
        $prestataires = Prestataire::with([
            'user',
            'services' => function ($query) use ($categoryId) {
                if ($categoryId) {
                    $query->where('category_id', $categoryId);
                }
            },
        ])
            ->when($categoryId, fn ($query) => $query->whereHas('services', fn ($sub) => $sub->where('category_id', $categoryId)))
            ->when($q && trim($q) !== '', fn ($query) => $query->whereHas('user', fn ($subQuery) => $subQuery->where('name', 'like', '%' . trim($q) . '%')))
            ->orderBy('id')
            ->limit(50)
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('services.index', [
            'category'     => $categoryId,
            'categories'   => $categories,
            'q'            => $q,
            'address'      => $request->get('address'),
            'type'         => $type,
            'lat'          => $request->get('lat'),
            'lng'          => $request->get('lng'),
            'prestataires' => $prestataires,
        ]);
    }
}
