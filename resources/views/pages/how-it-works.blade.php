@extends('layouts.app')

@section('title', 'Como Funciona')

@section('content')
<div class="page-content">
    <div class="container">
        {{-- Hero Section --}}
        <div class="text-center" style="margin-bottom: var(--space-3xl); text-align: center;">
            <h1 style="margin-bottom: var(--space-md);"><span class="text-gradient">Como Funciona o Clube do Pack</span></h1>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 1.1rem;">
                A plataforma mais moderna, segura e rentável para criadores de conteúdo premium e fãs no Brasil.
            </p>
        </div>

        {{-- Grid Two Roles --}}
        <div class="grid grid-2" style="margin-bottom: var(--space-3xl); display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--space-xl);">
            {{-- Column Creator --}}
            <div class="card card-body" style="border-top: 4px solid var(--accent-primary); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="font-size: 2.5rem; margin-bottom: var(--space-md);">👑</div>
                    <h2 style="font-size: 1.6rem; margin-bottom: var(--space-md);">Para Criadores</h2>
                    <p class="text-secondary" style="margin-bottom: var(--space-lg);">
                        Transforme sua influência em um negócio altamente rentável. Crie pacotes exclusivos ou venda assinaturas mensais recorrentes para sua audiência.
                    </p>
                    <ul style="list-style: none; padding: 0; margin-bottom: var(--space-xl); display: flex; flex-direction: column; gap: var(--space-md);">
                        <li style="display: flex; gap: var(--space-sm); align-items: flex-start;">
                            <span style="color: var(--accent-primary); font-weight: bold;">✓</span>
                            <span><strong>Taxa de apenas 15%:</strong> Fique com 85% de tudo o que vender.</span>
                        </li>
                        <li style="display: flex; gap: var(--space-sm); align-items: flex-start;">
                            <span style="color: var(--accent-primary); font-weight: bold;">✓</span>
                            <span><strong>Proteção anticópia:</strong> Visualizador de mídia seguro com proteção contra download não autorizado.</span>
                        </li>
                        <li style="display: flex; gap: var(--space-sm); align-items: flex-start;">
                            <span style="color: var(--accent-primary); font-weight: bold;">✓</span>
                            <span><strong>Monetização Dupla:</strong> Receba via vendas avulsas de packs ou assinaturas mensais.</span>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="btn btn-primary btn-block">Começar a Vender</a>
            </div>

            {{-- Column Customer --}}
            <div class="card card-body" style="border-top: 4px solid var(--info); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="font-size: 2.5rem; margin-bottom: var(--space-md);">💖</div>
                    <h2 style="font-size: 1.6rem; margin-bottom: var(--space-md);">Para Fãs</h2>
                    <p class="text-secondary" style="margin-bottom: var(--space-lg);">
                        Apoie seus criadores favoritos e tenha acesso imediato a coleções exclusivas de fotos, vídeos e bastidores inéditos em alta resolução.
                    </p>
                    <ul style="list-style: none; padding: 0; margin-bottom: var(--space-xl); display: flex; flex-direction: column; gap: var(--space-md);">
                        <li style="display: flex; gap: var(--space-sm); align-items: flex-start;">
                            <span style="color: var(--info); font-weight: bold;">✓</span>
                            <span><strong>Acesso Imediato:</strong> Desbloqueie o conteúdo segundos após o pagamento.</span>
                        </li>
                        <li style="display: flex; gap: var(--space-sm); align-items: flex-start;">
                            <span style="color: var(--info); font-weight: bold;">✓</span>
                            <span><strong>Biblioteca Privada:</strong> Acesse seus packs comprados a qualquer momento, pelo celular ou computador.</span>
                        </li>
                        <li style="display: flex; gap: var(--space-sm); align-items: flex-start;">
                            <span style="color: var(--info); font-weight: bold;">✓</span>
                            <span><strong>Discrição Total:</strong> Cobranças discretas na sua fatura e privacidade garantida.</span>
                        </li>
                    </ul>
                </div>
                <a href="/" class="btn btn-secondary btn-block">Explorar Marketplace</a>
            </div>
        </div>

        {{-- Steps Section --}}
        <div style="margin-bottom: var(--space-3xl);">
            <h2 style="text-align: center; margin-bottom: var(--space-2xl); font-size: 1.8rem;">O Caminho do Sucesso em 3 Passos</h2>
            <div class="grid grid-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-xl);">
                <div class="card card-body" style="text-align: center; position: relative;">
                    <div style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); width: 40px; height: 40px; border-radius: 50%; background: var(--accent-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; box-shadow: var(--shadow-md);">1</div>
                    <h3 style="margin-top: var(--space-sm); margin-bottom: var(--space-sm); font-size: 1.25rem;">Crie seu Perfil</h3>
                    <p class="text-secondary" style="font-size: 0.9rem;">
                        Cadastre-se gratuitamente, personalize sua página com bio, foto de perfil e escolha os preços do seu trabalho.
                    </p>
                </div>
                <div class="card card-body" style="text-align: center; position: relative;">
                    <div style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); width: 40px; height: 40px; border-radius: 50%; background: var(--accent-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; box-shadow: var(--shadow-md);">2</div>
                    <h3 style="margin-top: var(--space-sm); margin-bottom: var(--space-sm); font-size: 1.25rem;">Suba suas Mídias</h3>
                    <p class="text-secondary" style="font-size: 0.9rem;">
                        Faça upload de fotos e vídeos em nosso painel super simples, defina as capas de pré-visualização e precifique seus pacotes.
                    </p>
                </div>
                <div class="card card-body" style="text-align: center; position: relative;">
                    <div style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); width: 40px; height: 40px; border-radius: 50%; background: var(--accent-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; box-shadow: var(--shadow-md);">3</div>
                    <h3 style="margin-top: var(--space-sm); margin-bottom: var(--space-sm); font-size: 1.25rem;">Monetize e Divulgue</h3>
                    <p class="text-secondary" style="font-size: 0.9rem;">
                        Compartilhe seu link exclusivo nas redes sociais e receba pagamentos instantâneos via PIX direto em sua carteira.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
