@extends('layouts.app')

@section('title', 'Política de Privacidade')

@section('content')
<div class="page-content">
    <div class="container" style="max-width: 800px;">
        <div class="card card-body" style="padding: var(--space-2xl);">
            <h1 style="font-size: 2rem; margin-bottom: var(--space-md);"><span class="text-gradient">Política de Privacidade</span></h1>
            <p class="text-muted text-small" style="margin-bottom: var(--space-xl);">Última atualização: 31 de Maio de 2026</p>

            <div style="display: flex; flex-direction: column; gap: var(--space-xl); color: var(--text-secondary); line-height: 1.8;">
                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">1. Coleta de Informações</h2>
                    <p>
                        Coletamos informações essenciais para o funcionamento correto de nossa plataforma e para o processamento seguro de pagamentos. Isso inclui dados como seu nome, endereço de e-mail, username, e histórico de transações. Dados de pagamento bancário ou Pix são processados diretamente por nossos parceiros autorizados e não são armazenados em nossos servidores.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">2. Como Utilizamos Seus Dados</h2>
                    <p>
                        Seus dados pessoais são utilizados de forma estrita para:
                    </p>
                    <ul style="padding-left: var(--space-lg); margin-top: var(--space-xs); display: flex; flex-direction: column; gap: var(--space-xs);">
                        <li>• Gerenciar sua conta, compras e assinaturas.</li>
                        <li>• Processar transferências financeiras e saques de criadores de conteúdo.</li>
                        <li>• Enviar suporte técnico e comunicados importantes de segurança.</li>
                        <li>• Cumprir obrigações legais e financeiras.</li>
                    </ul>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">3. Sigilo e Compartilhamento</h2>
                    <p>
                        Temos o compromisso inabalável com a sua privacidade. Nós nunca vendemos, alugamos ou comercializamos dados de nossos usuários para terceiros. Dados transacionais só serão compartilhados quando estritamente necessários para o processamento do pagamento junto ao gateway financeiro parceiro.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">4. Segurança de Dados</h2>
                    <p>
                        Implementamos rigorosos protocolos de segurança física e eletrônica (como criptografia SSL/TLS) para proteger suas informações de acessos não autorizados, perdas ou adulterações. Nossos servidores passam por auditorias rotineiras de integridade e vulnerabilidade.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">5. Seus Direitos da LGPD</h2>
                    <p>
                        Como titular dos dados sob a Lei Geral de Proteção de Dados (LGPD), você possui o direito de solicitar a qualquer momento a confirmação da existência de tratamento, o acesso aos seus dados pessoais salvos, bem como a retificação ou exclusão permanente de seus dados, bastando entrar em contato com nosso suporte.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
