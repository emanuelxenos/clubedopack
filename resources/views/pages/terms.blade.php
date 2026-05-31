@extends('layouts.app')

@section('title', 'Termos de Uso')

@section('content')
<div class="page-content">
    <div class="container" style="max-width: 800px;">
        <div class="card card-body" style="padding: var(--space-2xl);">
            <h1 style="font-size: 2rem; margin-bottom: var(--space-md);"><span class="text-gradient">Termos de Uso</span></h1>
            <p class="text-muted text-small" style="margin-bottom: var(--space-xl);">Última atualização: 31 de Maio de 2026</p>

            <div style="display: flex; flex-direction: column; gap: var(--space-xl); color: var(--text-secondary); line-height: 1.8;">
                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">1. Aceitação dos Termos</h2>
                    <p>
                        Ao acessar e utilizar a plataforma Clube do Pack, você concorda em cumprir e ser regido por estes Termos de Uso. Se você não concordar com qualquer termo, pedimos que não utilize nossa plataforma ou serviços.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">2. Elegibilidade do Usuário</h2>
                    <p>
                        A plataforma destina-se a fins comerciais e de entretenimento privado. Você deve ter no mínimo 18 anos de idade para se cadastrar, adquirir conteúdos ou vender packs na plataforma. O cadastro de menores é terminantemente proibido.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">3. Propriedade Intelectual & Direitos Autorais</h2>
                    <p>
                        Todo conteúdo publicado por criadores (fotos, vídeos, textos) permanece sob propriedade exclusiva do respectivo autor. Ao adquirir um pack, o comprador obtém uma licença pessoal, não exclusiva, intransferível e revogável de visualização privada. É estritamente proibido:
                    </p>
                    <ul style="padding-left: var(--space-lg); margin-top: var(--space-xs); display: flex; flex-direction: column; gap: var(--space-xs);">
                        <li>• Baixar, copiar, gravar tela ou republicar mídias fora da plataforma.</li>
                        <li>• Compartilhar links ou logins de acesso com terceiros.</li>
                        <li>• Revender ou comercializar o conteúdo de qualquer criador.</li>
                    </ul>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">4. Políticas do Criador</h2>
                    <p>
                        Criadores de conteúdo são inteiramente responsáveis pela legalidade de seu material. É proibido publicar conteúdo que infrinja marcas registradas, exponha menores de idade, promova violência, ou não possua o consentimento de todas as partes envolvidas. Violadores terão contas banidas permanentemente sem reembolso de saldos.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">5. Limitação de Responsabilidade</h2>
                    <p>
                        O Clube do Pack funciona estritamente como um intermediador tecnológico e financeiro de hospedagem de conteúdos independentes e não se responsabiliza pelo comportamento individual ou pela exatidão do conteúdo disponibilizado por terceiros.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
