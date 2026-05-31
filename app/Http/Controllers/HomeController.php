<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Pack::with(['user', 'category'])
            ->where('is_active', true)
            ->whereHas('user', fn($q) => $q->where('is_active', true));

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderByDesc('views_count');
                break;
            case 'best-selling':
                $query->orderByDesc('downloads_count');
                break;
            case 'price-low':
                $query->orderBy('price');
                break;
            case 'price-high':
                $query->orderByDesc('price');
                break;
            default:
                $query->latest();
        }

        $packs = $query->paginate(12);

        $featuredPacks = Pack::with(['user', 'category'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->whereHas('user', fn($q) => $q->where('is_active', true))
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $topCreators = User::where('role', 'creator')
            ->where('is_active', true)
            ->withCount(['packs' => fn($q) => $q->where('is_active', true)])
            ->orderByDesc('packs_count')
            ->limit(8)
            ->get();

        return view('home', compact('packs', 'featuredPacks', 'categories', 'topCreators'));
    }
}
