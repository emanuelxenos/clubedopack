<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function stream(Media $media)
    {
        // 1. Check if user is logged in
        if (!auth()->check()) {
            abort(403, 'Acesso não autorizado.');
        }

        // 2. Check if user has access to the pack
        $user = auth()->user();
        if (!$user->hasAccessToPack($media->pack)) {
            abort(403, 'Você não tem acesso a esta mídia.');
        }

        // 3. Find file in local private storage
        $path = $media->file_path;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Arquivo de mídia não encontrado no disco seguro.');
        }

        // 4. Return file streaming response
        $file = Storage::disk('local')->get($path);
        $mimeType = Storage::disk('local')->mimeType($path);

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'private, max-age=86400');
    }
}
