<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Exibe a página de verificação de identidade para o criador logado.
     */
    public function showVerificationPage()
    {
        $user = Auth::user();

        // Se o usuário não for criador, redireciona ou define como criador para verificação
        if ($user->role !== 'creator') {
            return redirect()->route('home')->with('error', 'Apenas criadores podem verificar a identidade.');
        }

        // Se já estiver verificado, redireciona para o dashboard
        if ($user->verification_status === 'verified') {
            return redirect()->route('dashboard')->with('success', 'Sua identidade já foi verificada com sucesso!');
        }

        return view('creator.verify', compact('user'));
    }

    /**
     * Processa o resultado da verificação biométrica local enviada pelo front-end.
     * Como solicitado, não salvamos as imagens de documentos/selfie localmente por questões de segurança e privacidade.
     */
    public function submitVerification(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:verified,rejected',
            'score' => 'nullable|integer|min:0|max:100',
        ]);

        $user = Auth::user();

        if ($user->role !== 'creator') {
            return response()->json(['success' => false, 'message' => 'Não autorizado.'], 403);
        }

        // Atualiza os dados biométricos
        $user->verification_status = $request->status;
        $user->verification_score = $request->score;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $request->status === 'verified' 
                ? 'Identidade verificada com sucesso via inteligência artificial!' 
                : 'A verificação falhou. Por favor, tente novamente.',
            'status' => $user->verification_status
        ]);
    }
}
