<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Clube do Pack</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-page">
        <div class="auth-card animate-fade-in-up">
            <div class="auth-header">
                <a href="/" class="logo" style="justify-content: center; margin-bottom: var(--space-lg); display: flex; align-items: center; gap: 8px;">
                    <img src="/icon.png" alt="Clube do Pack Icon" style="height: 36px; width: 36px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(233, 30, 140, 0.25);">
                    <span style="font-weight: 700; background: linear-gradient(to right, var(--text-primary), #e91e8c); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Clube do Pack</span>
                </a>
                <h1>Crie sua conta</h1>
                <p>Junte-se à maior plataforma de conteúdo exclusivo</p>
            </div>

            @if($errors->any())
                <div style="margin-bottom: var(--space-lg);">
                    @foreach($errors->all() as $error)
                        <div class="toast toast-error" style="min-width: auto; margin-bottom: var(--space-xs);">
                            <span>✕</span>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Eu quero...</label>
                    <div class="role-selector">
                        <div class="role-option">
                            <input type="radio" name="role" id="role-customer" value="customer"
                                   {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                            <label for="role-customer">
                                <span class="role-icon">🛒</span>
                                <span class="role-name">Comprar</span>
                                <span class="role-desc">Acessar conteúdos exclusivos</span>
                            </label>
                        </div>
                        <div class="role-option">
                            <input type="radio" name="role" id="role-creator" value="creator"
                                   {{ old('role') === 'creator' ? 'checked' : '' }}>
                            <label for="role-creator">
                                <span class="role-icon">✨</span>
                                <span class="role-name">Criar</span>
                                <span class="role-desc">Publicar e monetizar</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="name">Nome completo</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Seu nome"
                           value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="seu@email.com"
                           value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="birth_date">Data de nascimento</label>
                    <input type="date" id="birth_date" name="birth_date" class="form-input"
                           value="{{ old('birth_date') }}" required>
                    <span style="font-size: 0.75rem; color: var(--text-tertiary); margin-top: 4px; display: block;">
                        ⚠️ Plataforma restrita a maiores de 18 anos.
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Senha</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Mínimo 6 caracteres"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                           placeholder="Repita a senha" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Criar Conta</button>
            </form>

            <div class="auth-footer">
                <p>Já tem uma conta? <a href="{{ route('login') }}">Faça login</a></p>
            </div>

            <div style="text-align: center; margin-top: var(--space-lg);">
                <button class="theme-toggle">🌙</button>
            </div>
        </div>
    </div>


</body>
</html>
