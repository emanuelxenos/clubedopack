@extends('layouts.app')

@section('title', 'Perguntas Frequentes (FAQ)')

@section('content')
<div class="page-content">
    <div class="container">
        {{-- Hero --}}
        <div class="text-center" style="margin-bottom: var(--space-3xl); text-align: center;">
            <h1 style="margin-bottom: var(--space-md);"><span class="text-gradient">Perguntas Frequentes</span></h1>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 1.1rem;">
                Tem alguma dúvida? Navegue por nossas perguntas mais frequentes de criadores e clientes.
            </p>
        </div>

        {{-- FAQ Container --}}
        <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: var(--space-md);">
            {{-- Question 1 --}}
            <div class="card card-body" style="padding: var(--space-lg);">
                <h3 style="font-size: 1.15rem; color: var(--text-primary); margin-bottom: var(--space-sm); display: flex; gap: var(--space-sm); align-items: center;">
                    <span style="color: var(--accent-primary);">Q.</span>
                    Como posso me cadastrar como criador?
                </h3>
                <p class="text-secondary" style="font-size: 0.95rem; padding-left: var(--space-lg);">
                    É muito simples! Basta criar uma conta comum clicando em "Criar Conta". Após logar, acesse seu perfil nas configurações e ative o modo "Criador de Conteúdo" para liberar seu painel completo de uploads e vendas.
                </p>
            </div>

            {{-- Question 2 --}}
            <div class="card card-body" style="padding: var(--space-lg);">
                <h3 style="font-size: 1.15rem; color: var(--text-primary); margin-bottom: var(--space-sm); display: flex; gap: var(--space-sm); align-items: center;">
                    <span style="color: var(--accent-primary);">Q.</span>
                    Quais são as formas de pagamento aceitas?
                </h3>
                <p class="text-secondary" style="font-size: 0.95rem; padding-left: var(--space-lg);">
                    Aceitamos pagamentos instantâneos via Pix para compras avulsas de packs e assinaturas recorrentes de criadores. É rápido, seguro e o conteúdo é desbloqueado no mesmo instante.
                </p>
            </div>

            {{-- Question 3 --}}
            <div class="card card-body" style="padding: var(--space-lg);">
                <h3 style="font-size: 1.15rem; color: var(--text-primary); margin-bottom: var(--space-sm); display: flex; gap: var(--space-sm); align-items: center;">
                    <span style="color: var(--accent-primary);">Q.</span>
                    O que é uma assinatura mensal e como funciona?
                </h3>
                <p class="text-secondary" style="font-size: 0.95rem; padding-left: var(--space-lg);">
                    Ao assinar um criador, você ganha acesso irrestrito a todos os packs criados por ele na plataforma enquanto a sua assinatura estiver ativa. O acesso é imediato e você pode cancelar quando quiser sem fidelidade.
                </p>
            </div>

            {{-- Question 4 --}}
            <div class="card card-body" style="padding: var(--space-lg);">
                <h3 style="font-size: 1.15rem; color: var(--text-primary); margin-bottom: var(--space-sm); display: flex; gap: var(--space-sm); align-items: center;">
                    <span style="color: var(--accent-primary);">Q.</span>
                    Meus dados pessoais e faturamento são confidenciais?
                </h3>
                <p class="text-secondary" style="font-size: 0.95rem; padding-left: var(--space-lg);">
                    Sim, absolutamente. Toda a transação é processada em ambiente altamente seguro e criptografado. As cobranças em sua fatura ou extrato aparecem com nomes discretos e neutros para garantir sua privacidade.
                </p>
            </div>

            {{-- Question 5 --}}
            <div class="card card-body" style="padding: var(--space-lg);">
                <h3 style="font-size: 1.15rem; color: var(--text-primary); margin-bottom: var(--space-sm); display: flex; gap: var(--space-sm); align-items: center;">
                    <span style="color: var(--accent-primary);">Q.</span>
                    Como posso entrar em contato direto para obter ajuda extra?
                </h3>
                <p class="text-secondary" style="font-size: 0.95rem; padding-left: var(--space-lg);">
                    Se você tiver qualquer problema técnico com uploads, compras ou saques, basta acessar a nossa página de <a href="{{ route('pages.contact') }}" style="font-weight: 600;">Contato</a> e nos enviar uma mensagem. Nossa equipe de suporte responde rápido!
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
