@extends('layouts.app')

@section('title', 'Contato')

@section('content')
<div class="page-content">
    <div class="container">
        {{-- Hero --}}
        <div class="text-center" style="margin-bottom: var(--space-3xl); text-align: center;">
            <h1 style="margin-bottom: var(--space-md);"><span class="text-gradient">Fale Conosco</span></h1>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 1.1rem;">
                Tem alguma dúvida, crítica, sugestão ou precisa de suporte comercial? Preencha o formulário abaixo.
            </p>
        </div>

        {{-- Contact Grid Layout --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: var(--space-xl); max-width: 960px; margin: 0 auto;">
            {{-- Contact Info Card --}}
            <div class="card card-body" style="background: var(--bg-secondary); border-color: var(--border-primary);">
                <h3 style="margin-bottom: var(--space-lg); font-size: 1.4rem;">Informações de Contato</h3>
                <p class="text-secondary" style="margin-bottom: var(--space-xl);">
                    Se preferir, você também pode nos contatar por outros canais oficiais. Respondemos em até 24 horas úteis.
                </p>

                <div style="display: flex; flex-direction: column; gap: var(--space-lg);">
                    <div style="display: flex; gap: var(--space-md); align-items: flex-start;">
                        <span style="font-size: 1.5rem;">📧</span>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">E-mail Comercial</div>
                            <div class="text-secondary" style="font-size: 0.9rem;">suporte@clubedopack.com.br</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: var(--space-md); align-items: flex-start;">
                        <span style="font-size: 1.5rem;">💬</span>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">Redes Sociais</div>
                            <div class="text-secondary" style="font-size: 0.9rem;">@clubedopack</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: var(--space-md); align-items: flex-start;">
                        <span style="font-size: 1.5rem;">⏱️</span>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">Horário de Atendimento</div>
                            <div class="text-secondary" style="font-size: 0.9rem;">Segunda a Sexta: 09:00 às 18:00</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Form Card --}}
            <div class="card card-body">
                <h3 style="margin-bottom: var(--space-lg); font-size: 1.4rem;">Envie sua Mensagem</h3>

                <form action="/contato" method="POST" style="display: flex; flex-direction: column; gap: var(--space-md);">
                    @csrf
                    <div>
                        <label for="name" style="display: block; margin-bottom: 6px; font-size: 0.9rem; font-weight: 500; color: var(--text-secondary);">Nome Completo</label>
                        <input type="text" name="name" id="name" required class="form-input" style="width: 100%; padding: 10px 14px; background: var(--bg-input); border: 1px solid var(--border-primary); border-radius: var(--radius-sm); color: var(--text-primary); outline: none; transition: border-color var(--transition-fast);" placeholder="Seu nome">
                    </div>
                    <div>
                        <label for="email" style="display: block; margin-bottom: 6px; font-size: 0.9rem; font-weight: 500; color: var(--text-secondary);">E-mail para Retorno</label>
                        <input type="email" name="email" id="email" required class="form-input" style="width: 100%; padding: 10px 14px; background: var(--bg-input); border: 1px solid var(--border-primary); border-radius: var(--radius-sm); color: var(--text-primary); outline: none; transition: border-color var(--transition-fast);" placeholder="seu@email.com">
                    </div>
                    <div>
                        <label for="subject" style="display: block; margin-bottom: 6px; font-size: 0.9rem; font-weight: 500; color: var(--text-secondary);">Assunto</label>
                        <input type="text" name="subject" id="subject" required class="form-input" style="width: 100%; padding: 10px 14px; background: var(--bg-input); border: 1px solid var(--border-primary); border-radius: var(--radius-sm); color: var(--text-primary); outline: none; transition: border-color var(--transition-fast);" placeholder="Qual é o assunto?">
                    </div>
                    <div>
                        <label for="message" style="display: block; margin-bottom: 6px; font-size: 0.9rem; font-weight: 500; color: var(--text-secondary);">Mensagem</label>
                        <textarea name="message" id="message" rows="5" required class="form-input" style="width: 100%; padding: 10px 14px; background: var(--bg-input); border: 1px solid var(--border-primary); border-radius: var(--radius-sm); color: var(--text-primary); outline: none; transition: border-color var(--transition-fast); resize: vertical;" placeholder="Escreva sua dúvida ou mensagem detalhadamente..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: var(--space-sm);">
                        Enviar Mensagem ➔
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
