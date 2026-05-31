<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(string $username)
    {
        $creator = User::where('username', $username)
            ->where('role', 'creator')
            ->where('is_active', true)
            ->firstOrFail();

        $packs = $creator->packs()
            ->where('is_active', true)
            ->with('category')
            ->latest()
            ->paginate(12);

        $isSubscribed = false;
        if (auth()->check()) {
            $isSubscribed = auth()->user()->isSubscribedTo($creator);
        }

        $subscribersCount = $creator->active_subscribers_count;
        $packsCount = $creator->packs()->where('is_active', true)->count();

        return view('profile.show', compact('creator', 'packs', 'isSubscribed', 'subscribersCount', 'packsCount'));
    }
}
