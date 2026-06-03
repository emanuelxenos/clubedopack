<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function stream(\Illuminate\Http\Request $request, Media $media)
    {
        // 0. Validação extra: Bloquear se o IP atual for diferente do IP que assinou a URL
        if ($request->query('ip') && $request->query('ip') !== $request->ip()) {
            abort(403, 'Acesso bloqueado por restrição de rede (IP Incompatível).');
        }

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

        // 4. Return secure binary streamed response with Range Support
        $absolutePath = Storage::disk('local')->path($path);

        return response()->file($absolutePath, [
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Accept-Ranges' => 'bytes'
        ]);
    }
}
