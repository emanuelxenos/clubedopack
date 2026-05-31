@extends('layouts.app')

@section('title', 'Política de Cookies')

@section('content')
<div class="page-content">
    <div class="container" style="max-width: 800px;">
        <div class="card card-body" style="padding: var(--space-2xl);">
            <h1 style="font-size: 2rem; margin-bottom: var(--space-md);"><span class="text-gradient">Política de Cookies</span></h1>
            <p class="text-muted text-small" style="margin-bottom: var(--space-xl);">Última atualização: 31 de Maio de 2026</p>

            <div style="display: flex; flex-direction: column; gap: var(--space-xl); color: var(--text-secondary); line-height: 1.8;">
                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">1. O que são Cookies?</h2>
                    <p>
                        Cookies são pequenos arquivos de texto que são salvos no seu computador ou dispositivo móvel por sites que você visita. Eles ajudam a tornar o site mais eficiente, personalizado, seguro e ajudam a armazenar preferências de uso do usuário.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">2. Como Utilizamos os Cookies</h2>
                    <p>
                        Utilizamos cookies para diversas finalidades cruciais em nossa plataforma, que podem ser classificadas da seguinte maneira:
                    </p>
                    <ul style="padding-left: var(--space-lg); margin-top: var(--space-xs); display: flex; flex-direction: column; gap: var(--space-xs);">
                        <li>• <strong>Essenciais:</strong> Cookies necessários para que você consiga logar, navegar com segurança e realizar transações na plataforma. Sem eles, o site não funciona corretamente.</li>
                        <li>• <strong>Preferências:</strong> Cookies que salvam suas preferências locais de navegação, como a sua preferência de tema visual (Dark Mode ou Light Mode).</li>
                        <li>• <strong>Segurança:</strong> Cookies utilizados para prevenir logins abusivos ou múltiplos fraudulentos e para proteger sua conta de acessos indesejados.</li>
                    </ul>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">3. Cookies de Terceiros</h2>
                    <p>
                        Para o processamento e segurança dos pagamentos, nossos parceiros de gateway de pagamento autorizados podem salvar cookies em seu navegador para validar a integridade da transação e prevenir fraudes contra o seu cartão de crédito ou conta bancária.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">4. Como Controlar os Cookies</h2>
                    <p>
                        Você possui total liberdade para configurar, bloquear ou excluir cookies diretamente nas configurações de privacidade do seu navegador (Chrome, Firefox, Safari, Edge, etc.). Note, contudo, que desabilitar os cookies essenciais impedirá que você faça login e utilize as ferramentas logadas do Clube do Pack.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
