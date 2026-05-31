@extends('layouts.app')

@section('title', 'Preços')

@section('content')
<div class="page-content">
    <div class="container">
        {{-- Hero Section --}}
        <div class="text-center" style="margin-bottom: var(--space-3xl); text-align: center;">
            <h1 style="margin-bottom: var(--space-md);"><span class="text-gradient">Simples, Justo e Sem Mensalidades</span></h1>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 1.1rem;">
                Cobramos apenas quando você vende. Sem taxas de setup, sem custos ocultos e sem mensalidade.
            </p>
        </div>

        {{-- Pricing Cards Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--space-xl); margin-bottom: var(--space-3xl); justify-content: center; max-width: 900px; margin-left: auto; margin-right: auto;">
            {{-- Free Card --}}
            <div class="card card-body" style="text-align: center; display: flex; flex-direction: column; justify-content: space-between; padding: var(--space-2xl);">
                <div>
                    <h2 style="font-size: 1.5rem; margin-bottom: var(--space-xs); font-family: var(--font-heading);">Cadastro & Painel</h2>
                    <div style="font-size: 3rem; font-weight: 800; color: var(--text-primary); margin: var(--space-md) 0; font-family: var(--font-heading);">
                        R$ 0
                    </div>
                    <p class="text-secondary" style="margin-bottom: var(--space-lg); font-size: 0.95rem;">
                        Você tem acesso total à nossa tecnologia, painel de upload, gerenciamento de assinaturas e suporte sem pagar nada.
                    </p>
                    <div style="border-top: 1px solid var(--border-primary); padding-top: var(--space-md); margin-top: var(--space-md); text-align: left;">
                        <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: var(--space-sm); font-size: 0.9rem;">
                            <li>✓ Criação de perfil ilimitada</li>
                            <li>✓ Upload ilimitado de fotos e vídeos</li>
                            <li>✓ Integração automática com Gateway Pix</li>
                            <li>✓ Painel financeiro em tempo real</li>
                        </ul>
                    </div>
                </div>
                <a href="{{ route('register') }}" class="btn btn-secondary btn-block" style="margin-top: var(--space-xl);">Criar Conta Grátis</a>
            </div>

            {{-- Fee Card --}}
            <div class="card card-body" style="border: 2px solid var(--accent-primary); text-align: center; display: flex; flex-direction: column; justify-content: space-between; padding: var(--space-2xl); position: relative; box-shadow: var(--shadow-glow);">
                <div style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: var(--accent-gradient); color: white; padding: 4px 16px; border-radius: var(--radius-xl); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Apenas sobre vendas</div>
                <div>
                    <h2 style="font-size: 1.5rem; margin-bottom: var(--space-xs); font-family: var(--font-heading);">Taxa de Serviço</h2>
                    <div style="font-size: 3rem; font-weight: 800; color: var(--accent-primary); margin: var(--space-md) 0; font-family: var(--font-heading);">
                        15%
                    </div>
                    <p class="text-secondary" style="margin-bottom: var(--space-lg); font-size: 0.95rem;">
                        Mantemos apenas uma pequena taxa de 15% sobre cada venda ou assinatura concluída para cobrir custos operacionais e de infraestrutura.
                    </p>
                    <div style="border-top: 1px solid var(--border-primary); padding-top: var(--space-md); margin-top: var(--space-md); text-align: left;">
                        <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: var(--space-sm); font-size: 0.9rem;">
                            <li>✓ <strong>85% de repasse:</strong> Fique com o maior split do mercado</li>
                            <li>✓ Proteção contra fraudes e estornos</li>
                            <li>✓ Armazenamento de mídia premium ilimitado</li>
                            <li>✓ Suporte VIP dedicado ao criador</li>
                        </ul>
                    </div>
                </div>
                <a href="{{ route('register') }}" class="btn btn-primary btn-block" style="margin-top: var(--space-xl);">Começar a Faturar</a>
            </div>
        </div>

        {{-- FAQ Mini section --}}
        <div class="card card-body" style="max-width: 800px; margin: 0 auto; padding: var(--space-xl);">
            <h3 style="margin-bottom: var(--space-lg); text-align: center;">Perguntas Frequentes sobre Pagamentos</h3>
            <div style="display: flex; flex-direction: column; gap: var(--space-lg);">
                <div>
                    <h4 style="font-size: 1rem; margin-bottom: var(--space-xs); color: var(--text-primary);">Quando eu recebo meus ganhos?</h4>
                    <p class="text-secondary" style="font-size: 0.9rem;">
                        O saldo de vendas é liberado para saque em sua conta bancária de forma rápida. Para vendas via Pix, os valores ficam disponíveis para saque em até 2 dias úteis.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 1rem; margin-bottom: var(--space-xs); color: var(--text-primary);">Existe valor mínimo de saque?</h4>
                    <p class="text-secondary" style="font-size: 0.9rem;">
                        Sim, o valor mínimo para solicitar a transferência bancária/Pix para sua conta é de R$ 50,00.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 1rem; margin-bottom: var(--space-xs); color: var(--text-primary);">Há custos para fazer saques?</h4>
                    <p class="text-secondary" style="font-size: 0.9rem;">
                        Não! Nós não cobramos nenhuma taxa para processar seus saques bancários. A transferência via Pix é 100% gratuita.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
