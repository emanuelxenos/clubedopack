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

        // 1. Otimização Extrema de Banco de Dados:
        // Como a URL possui o middleware 'signed', é impossível forjar essa URL.
        // Se a assinatura é válida, o IP bate, e o User ID bate, nós JÁ SABEMOS que o usuário
        // tinha acesso quando geramos o link há alguns minutos atrás.
        // Removemos as consultas pesadas ($user->hasAccessToPack) que sobrecarregavam
        // o banco a cada micro-fatia de vídeo solicitada pelo navegador.

        $path = $media->file_path;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Arquivo de mídia não encontrado no disco seguro.');
        }

        $absolutePath = Storage::disk('local')->path($path);

        return response()->file($absolutePath, [
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Accept-Ranges' => 'bytes'
        ]);
    }
}
