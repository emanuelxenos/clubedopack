@extends('layouts.app')

@section('title', 'Central de Ajuda')

@section('content')
<div class="page-content">
    <div class="container">
        {{-- Hero --}}
        <div class="text-center" style="margin-bottom: var(--space-3xl); text-align: center;">
            <h1 style="margin-bottom: var(--space-md);"><span class="text-gradient">Como podemos ajudar?</span></h1>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 1.1rem;">
                Encontre tutoriais, respostas e guias rápidos para tirar suas dúvidas sobre o Clube do Pack.
            </p>
        </div>

        {{-- Help Categories Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-3xl);">
            {{-- Category 1 --}}
            <div class="card card-body" style="transition: transform var(--transition-base);">
                <div style="font-size: 2rem; margin-bottom: var(--space-sm);">🚀</div>
                <h3 style="font-size: 1.2rem; margin-bottom: var(--space-sm);">Primeiros Passos</h3>
                <p class="text-secondary" style="font-size: 0.9rem; margin-bottom: var(--space-md);">
                    Aprenda a criar sua conta, preencher seu perfil e começar a explorar a plataforma de maneira rápida.
                </p>
                <a href="{{ route('pages.faq') }}" style="font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    Ver artigos ➔
                </a>
            </div>

            {{-- Category 2 --}}
            <div class="card card-body">
                <div style="font-size: 2rem; margin-bottom: var(--space-sm);">📸</div>
                <h3 style="font-size: 1.2rem; margin-bottom: var(--space-sm);">Área do Criador</h3>
                <p class="text-secondary" style="font-size: 0.9rem; margin-bottom: var(--space-md);">
                    Como criar novos packs, configurar preços de assinaturas, ver seus repasses instantâneos e turbinar suas vendas.
                </p>
                <a href="{{ route('pages.faq') }}" style="font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    Ver artigos ➔
                </a>
            </div>

            {{-- Category 3 --}}
            <div class="card card-body">
                <div style="font-size: 2rem; margin-bottom: var(--space-sm);">💳</div>
                <h3 style="font-size: 1.2rem; margin-bottom: var(--space-sm);">Compras e Assinaturas</h3>
                <p class="text-secondary" style="font-size: 0.9rem; margin-bottom: var(--space-md);">
                    Dúvidas sobre pagamento via Pix, acesso aos conteúdos adquiridos e cancelamento de assinaturas.
                </p>
                <a href="{{ route('pages.faq') }}" style="font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    Ver artigos ➔
                </a>
            </div>

            {{-- Category 4 --}}
            <div class="card card-body">
                <div style="font-size: 2rem; margin-bottom: var(--space-sm);">🛡️</div>
                <h3 style="font-size: 1.2rem; margin-bottom: var(--space-sm);">Segurança e Termos</h3>
                <p class="text-secondary" style="font-size: 0.9rem; margin-bottom: var(--space-md);">
                    Entenda como protegemos seu conteúdo, nossa política de privacidade e os termos de uso da comunidade.
                </p>
                <a href="{{ route('pages.terms') }}" style="font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    Ver termos ➔
                </a>
            </div>
        </div>

        {{-- Support Ticket callout --}}
        <div class="card card-body" style="text-align: center; max-width: 800px; margin: 0 auto; padding: var(--space-2xl); border-top: 4px solid var(--accent-primary);">
            <h3 style="margin-bottom: var(--space-sm);">Não encontrou o que procurava?</h3>
            <p class="text-secondary" style="margin-bottom: var(--space-lg); max-width: 500px; margin-left: auto; margin-right: auto;">
                Nossa equipe de suporte está disponível 24 horas por dia, 7 dias por semana, para ajudar você com qualquer problema.
            </p>
            <a href="{{ route('pages.contact') }}" class="btn btn-primary">Falar com o Suporte</a>
        </div>
    </div>
</div>
@endsection
