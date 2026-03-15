<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:100']]);
        Category::create($validated);
        return back()->with('success', 'Catégorie créée.');
    }

    public function update(Request $request, $id)
    {
        $cat = Category::findOrFail($id);
        $validated = $request->validate(['name' => ['required', 'string', 'max:100']]);
        $cat->update($validated);
        return back()->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Request $request, $id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }
}
