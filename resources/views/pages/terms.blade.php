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
                    <p style="margin-top: var(--space-sm); font-size: 0.9rem; color: #ff6b6b; font-weight: 500;">
                        ⚠️ Qualquer tentativa de vazamento, gravação de tela ou distribuição não autorizada de mídias constitui crime de violação de direito autoral (Lei nº 9.610/98) e resultará em imediato banimento da conta e abertura de processo judicial de indenização cível e criminal contra o infrator.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">4. Políticas do Criador</h2>
                    <p>
                        Criadores de conteúdo são inteiramente responsáveis pela legalidade de seu material. É proibido publicar conteúdo que infrinja marcas registradas, exponha menores de idade, promova violência, ou não possua o consentimento de todas as partes envolvidas. Violadores terão contas banidas permanentemente sem reembolso de saldos.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">5. Política de Compras, Acesso e Reembolsos</h2>
                    <p>
                        A plataforma Clube do Pack concede aos criadores controle total sobre seus respectivos perfis e packs cadastrados. Criadores podem atualizar, editar ou excluir seus conteúdos e pacotes a qualquer momento e a seu livre critério. Ao efetuar uma compra ou assinatura:
                    </p>
                    <ul style="padding-left: var(--space-lg); margin-top: var(--space-xs); display: flex; flex-direction: column; gap: var(--space-xs);">
                        <li>• O comprador reconhece que o acesso ao conteúdo adquirido está condicionado à permanência do material ativo na plataforma pelo criador.</li>
                        <li>• No caso de remoção de um pack ou encerramento de perfil por decisão do próprio criador, o cliente poderá perder o acesso à visualização do conteúdo de forma definitiva.</li>
                        <li>• Não haverá devolução de valores, reembolso ou estorno de pagamentos em virtude de exclusão de mídias e packs por parte do criador, cabendo ao cliente aceitar a autonomia de gestão de conteúdo do autor no momento da compra.</li>
                    </ul>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">6. Limitação de Responsabilidade</h2>
                    <p>
                        O Clube do Pack funciona estritamente como um intermediador tecnológico e financeiro de hospedagem de conteúdos independentes e não se responsabiliza pelo comportamento individual ou pela exatidão do conteúdo disponibilizado por terceiros.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">7. Política Antifraude e Abuso de Chargeback (Estornos)</h2>
                    <p>
                        Abertura de disputas ou contestações de pagamento de má-fé junto às emissoras de cartão de crédito (chargeback) após a visualização dos conteúdos adquiridos configura fraude contratual. Nesses casos, a plataforma reserva-se o direito de suspender ou banir permanentemente a conta do usuário e reportar os dados associados à transação para gateways de pagamento, órgãos de proteção ao crédito (como Serasa/SPC) e autoridades policiais.
                    </p>
                </section>

                <section>
                    <h2 style="font-size: 1.35rem; color: var(--text-primary); margin-bottom: var(--space-sm);">8. Modificações dos Termos</h2>
                    <p>
                        O Clube do Pack reserva-se o direito de atualizar e alterar estes Termos de Uso e suas taxas de intermediação a qualquer momento. O uso contínuo da plataforma após tais atualizações constituirá aceitação implícita das novas diretrizes contratuais por parte do usuário.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
