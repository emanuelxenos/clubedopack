<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function stream(\Illuminate\Http\Request $request, Media $media)
    {
        // 0. Validação de IP e Usuário (garante que quem está baixando o chunk é o dono do link)
        if ($request->query('ip') && $request->query('ip') !== $request->ip()) {
            abort(403, 'Acesso bloqueado por restrição de rede (IP Incompatível).');
        }
        
        if ($request->query('uid') && $request->query('uid') != auth()->id()) {
            abort(403, 'Acesso bloqueado (Usuário Incompatível).');
        }

        $path = $media->file_path;
        $disk = Storage::disk('local'); // Em filesystems.php, 'local' é mapeado para R2 se STORAGE_MODE=r2

        // Delegação de Tráfego: R2 (S3) Nativo
        if (env('STORAGE_MODE', 'local') === 'r2') {
            // A Cloudflare R2 gera um link direto (Temporary Signed URL da AWS S3) que expira.
            // Isso tira 100% da carga de tráfego e de processamento de chunking do servidor Laravel.
            $url = $disk->temporaryUrl($path, now()->addHours(6));
            return redirect()->away($url);
        }

        // Fallback: Armazenamento Local Tradicional
        if (!$disk->exists($path)) {
            abort(404, 'Arquivo de mídia não encontrado no disco seguro.');
        }

        $absolutePath = $disk->path($path);

        return response()->file($absolutePath, [
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Accept-Ranges' => 'bytes'
        ]);
    }
}
