@extends('layouts.admin')

@section('title', 'Admin - Configurações')

@section('admin-content')
    <h1 style="margin-bottom: var(--space-2xl);">⚙️ Configurações da Plataforma</h1>

    <div style="max-width: 600px;">
        <div class="card" style="padding:var(--space-xl);margin-bottom:var(--space-xl);">
            <h3 style="margin-bottom:var(--space-lg);">💰 Financeiro</h3>
            <div class="form-group">
                <label class="form-label">Taxa da Plataforma</label>
                <div class="form-input" style="cursor:default;background:var(--bg-tertiary);">
                    {{ config('app.platform_fee_percent') }}%
                </div>
                <div class="form-hint">Configurável via variável PLATFORM_FEE_PERCENT no arquivo .env</div>
            </div>
        </div>

        <div class="card" style="padding:var(--space-xl);margin-bottom:var(--space-xl);">
            <h3 style="margin-bottom:var(--space-lg);">☁️ Armazenamento</h3>
            <div class="form-group">
                <label class="form-label">Modo de Armazenamento</label>
                <div class="form-input" style="cursor:default;background:var(--bg-tertiary);">
                    {{ config('app.storage_mode') === 'r2' ? 'Cloudflare R2 (Produção)' : 'Local (Desenvolvimento)' }}
                </div>
                <div class="form-hint">Configurável via STORAGE_MODE no .env. Use "local" para testes e "r2" para produção.</div>
            </div>
        </div>

        <div class="card" style="padding:var(--space-xl);margin-bottom:var(--space-xl);">
            <h3 style="margin-bottom:var(--space-lg);">🔌 Gateway de Pagamento</h3>
            <div class="form-group">
                <label class="form-label">Gateway Ativo</label>
                <div class="form-input" style="cursor:default;background:var(--bg-tertiary);">
                    Mock (Desenvolvimento)
                </div>
                <div class="form-hint">O sistema está preparado para integração com Mercado Pago, Asaas ou PagSeguro. Configure as credenciais no .env quando estiver pronto para produção.</div>
            </div>
        </div>

        <div class="card" style="padding:var(--space-xl);">
            <h3 style="margin-bottom:var(--space-lg);">📊 Informações do Sistema</h3>
            <ul class="pack-details-list" style="list-style:none;">
                <li><span>PHP</span><strong>{{ phpversion() }}</strong></li>
                <li><span>Laravel</span><strong>{{ app()->version() }}</strong></li>
                <li><span>Banco de Dados</span><strong>{{ config('database.default') }}</strong></li>
                <li><span>Timezone</span><strong>{{ config('app.timezone') }}</strong></li>
            </ul>
        </div>
    </div>
@endsection
