<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function stream(\Illuminate\Http\Request $request, Media $media)
    {
        \Illuminate\Support\Facades\Log::info("Stream solicitado: Media ID {$media->id} pelo User ID " . auth()->id());

        // Validação de Usuário
        if ($request->query('uid') && $request->query('uid') != auth()->id()) {
            \Illuminate\Support\Facades\Log::warning("Bloqueio UID: Esperado {$request->query('uid')}, Recebido " . auth()->id());
            abort(403, 'Acesso bloqueado (Usuário Incompatível).');
        }

        $path = $media->file_path;
        $disk = Storage::disk('local'); // Mapeado para R2 se STORAGE_MODE=r2

        \Illuminate\Support\Facades\Log::info("Storage mode: " . env('STORAGE_MODE', 'local') . " | Path: {$path}");

        // Delegação de Tráfego: R2 (S3) Nativo
        if (env('STORAGE_MODE', 'local') === 'r2') {
            if ($disk->exists($path)) {
                $url = $disk->temporaryUrl($path, now()->addHours(6));
                \Illuminate\Support\Facades\Log::info("R2 Redirect URL gerada: {$url}");
                return redirect()->away($url);
            }
            
            \Illuminate\Support\Facades\Log::info("Arquivo não encontrado no R2, tentando fallback local...");
            $realLocalDisk = Storage::build([
                'driver' => 'local',
                'root' => storage_path('app'),
            ]);
            
            if ($realLocalDisk->exists($path)) {
                \Illuminate\Support\Facades\Log::info("Fallback local encontrado! Servindo via response()->file.");
                return response()->file($realLocalDisk->path($path), [
                    'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Accept-Ranges' => 'bytes'
                ]);
            }
            
            \Illuminate\Support\Facades\Log::error("Arquivo NÃO EXISTE nem no R2 nem no local: {$path}");
            abort(404, 'Arquivo de mídia não encontrado nem no R2 nem no disco local.');
        }

        if (!$disk->exists($path)) {
            \Illuminate\Support\Facades\Log::error("Arquivo NÃO EXISTE no local (modo local): {$path}");
            abort(404, 'Arquivo de mídia não encontrado no disco seguro.');
        }

        \Illuminate\Support\Facades\Log::info("Servindo arquivo local (modo local)...");
        return response()->file($disk->path($path), [
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Accept-Ranges' => 'bytes'
        ]);
    }
}
