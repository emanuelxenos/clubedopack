<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use Illuminate\Http\Request;

class PackController extends Controller
{
    public function show(string $slug)
    {
        $pack = Pack::where('slug', $slug)
            ->where('is_active', true)
            ->with(['user', 'category', 'media'])
            ->firstOrFail();

        $pack->incrementViews();

        $hasAccess = false;
        if (auth()->check()) {
            $hasAccess = auth()->user()->hasAccessToPack($pack);
        }

        $relatedPacks = Pack::where('category_id', $pack->category_id)
            ->where('id', '!=', $pack->id)
            ->where('is_active', true)
            ->with('user')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('packs.show', compact('pack', 'hasAccess', 'relatedPacks'));
    }
}
