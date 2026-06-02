<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalPacks = $user->packs()->count();
        $activePacks = $user->packs()->where('is_active', true)->count();
        $totalViews = $user->packs()->sum('views_count');
        $totalSales = $user->packs()->withCount(['purchases' => fn($q) => $q->where('status', 'confirmed')])->get()->sum('purchases_count');
        $totalRevenue = $user->transactions()->where('status', 'completed')->sum('creator_amount');
        $subscribersCount = $user->active_subscribers_count;

        $recentPacks = $user->packs()->with('category')->latest()->limit(5)->get();

        return view('dashboard.index', compact(
            'totalPacks', 'activePacks', 'totalViews', 'totalSales',
            'totalRevenue', 'subscribersCount', 'recentPacks'
        ));
    }

    public function packs()
    {
        $packs = auth()->user()->packs()
            ->with('category')
            ->withCount(['purchases' => fn($q) => $q->where('status', 'confirmed')])
            ->latest()
            ->paginate(10);

        return view('dashboard.packs', compact('packs'));
    }

    public function createPack()
    {
        $user = auth()->user();
        if ($user->verification_status !== 'verified') {
            return redirect()->route('dashboard.verify')->with('warning', 'Para publicar e criar novos packs, primeiro você precisa concluir a verificação biométrica de idade.');
        }

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('dashboard.pack-form', compact('categories'));
    }

    public function storePack(Request $request)
    {
        $user = auth()->user();
        if ($user->verification_status !== 'verified') {
            return redirect()->route('dashboard.verify')->with('error', 'Ação não permitida. Verificação de idade obrigatória.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1',
            'cover_image' => 'nullable|image|max:5120',
            'media_files.*' => 'nullable|file|max:102400',
        ]);

        $pack = new Pack();
        $pack->user_id = auth()->id();
        $pack->title = $validated['title'];
        $pack->description = $validated['description'];
        $pack->category_id = $validated['category_id'];
        $pack->price = $validated['price'];
        $pack->is_active = true;

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('packs/covers', 'public');
            $pack->cover_image_path = $path;
        }

        $pack->save();

        // Handle media files
        if ($request->hasFile('media_files')) {
            $order = 0;
            foreach ($request->file('media_files') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $fileType = in_array($extension, ['mp4', 'mov', 'avi', 'webm']) ? 'video' : 'image';
                $path = $file->store('private/packs/media/' . $pack->id, 'local');

                Media::create([
                    'pack_id' => $pack->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'size' => $file->getSize(),
                    'sort_order' => $order++,
                ]);
            }

            $pack->update(['media_count' => $order]);

            // Auto-set cover if not provided
            if (!$pack->cover_image_path) {
                $firstImage = $pack->media()->where('file_type', 'image')->first();
                if ($firstImage) {
                    $pack->update(['cover_image_path' => $firstImage->file_path]);
                }
            }
        }

        return redirect('/dashboard/packs')->with('success', 'Pack criado com sucesso!');
    }

    public function editPack(Pack $pack)
    {
        if ($pack->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $pack->load('media');

        return view('dashboard.pack-form', compact('pack', 'categories'));
    }

    public function updatePack(Request $request, Pack $pack)
    {
        if ($pack->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1',
            'cover_image' => 'nullable|image|max:5120',
            'media_files.*' => 'nullable|file|max:102400',
            'is_active' => 'nullable|boolean',
        ]);

        $pack->title = $validated['title'];
        $pack->description = $validated['description'];
        $pack->category_id = $validated['category_id'];
        $pack->price = $validated['price'];
        $pack->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('cover_image')) {
            if ($pack->cover_image_path) {
                Storage::disk('public')->delete($pack->cover_image_path);
            }
            $path = $request->file('cover_image')->store('packs/covers', 'public');
            $pack->cover_image_path = $path;
        }

        $pack->save();

        // Handle new media files
        if ($request->hasFile('media_files')) {
            $maxOrder = $pack->media()->max('sort_order') ?? -1;
            $order = $maxOrder + 1;

            foreach ($request->file('media_files') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $fileType = in_array($extension, ['mp4', 'mov', 'avi', 'webm']) ? 'video' : 'image';
                $path = $file->store('private/packs/media/' . $pack->id, 'local');

                Media::create([
                    'pack_id' => $pack->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'size' => $file->getSize(),
                    'sort_order' => $order++,
                ]);
            }

            $pack->update(['media_count' => $pack->media()->count()]);
        }

        return redirect('/dashboard/packs')->with('success', 'Pack atualizado com sucesso!');
    }

    public function destroyPack(Pack $pack)
    {
        if ($pack->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete media files
        foreach ($pack->media as $media) {
            Storage::disk('local')->delete($media->file_path);
            if ($media->thumbnail_path) {
                Storage::disk('local')->delete($media->thumbnail_path);
            }
        }

        if ($pack->cover_image_path) {
            Storage::disk('public')->delete($pack->cover_image_path);
        }

        $pack->delete();

        return redirect('/dashboard/packs')->with('success', 'Pack excluído com sucesso!');
    }

    public function deleteMedia(Media $media)
    {
        $pack = $media->pack;
        if ($pack->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('local')->delete($media->file_path);
        if ($media->thumbnail_path) {
            Storage::disk('local')->delete($media->thumbnail_path);
        }

        $media->delete();
        $pack->update(['media_count' => $pack->media()->count()]);

        return back()->with('success', 'Arquivo removido com sucesso!');
    }

    public function earnings()
    {
        $user = auth()->user();

        $totalEarnings = $user->transactions()->where('status', 'completed')->sum('creator_amount');
        $monthlyEarnings = $user->transactions()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('creator_amount');

        $transactions = $user->transactions()->latest()->paginate(15);

        return view('dashboard.earnings', compact('totalEarnings', 'monthlyEarnings', 'transactions'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'subscription_price' => 'nullable|numeric|min:0',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:5120',
            'pix_key_type' => 'nullable|string|in:cpf,email,phone,random',
            'pix_key' => 'nullable|string|max:255',
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->bio = $validated['bio'];
        $user->subscription_price = $validated['subscription_price'];
        $user->pix_key_type = $validated['pix_key_type'] ?? null;
        $user->pix_key = $validated['pix_key'] ?? null;

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($user->banner_path) {
                Storage::disk('public')->delete($user->banner_path);
            }
            $user->banner_path = $request->file('banner')->store('banners', 'public');
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
