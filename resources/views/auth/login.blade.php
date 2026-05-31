@section('hide-footer', true)

<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Clube do Pack</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card animate-fade-in-up">
            <div class="auth-header">
                <a href="/" class="logo" style="justify-content: center; margin-bottom: var(--space-lg);">
                    <div class="logo-icon">🔥</div>
                    <span>Clube do Pack</span>
                </a>
                <h1>Bem-vindo de volta</h1>
                <p>Entre na sua conta para continuar</p>
            </div>

            @if($errors->any())
                <div class="toast toast-error" style="margin-bottom: var(--space-lg); min-width: auto;">
                    <span>✕</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="seu@email.com"
                           value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Senha</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••"
                           required>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: var(--space-sm);">
                    <input type="checkbox" id="remember" name="remember" style="accent-color: var(--accent-primary);">
                    <label for="remember" style="font-size: 0.9rem; color: var(--text-secondary); cursor: pointer;">Lembrar-me</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Entrar</button>
            </form>

            <div class="auth-footer">
                <p>Não tem uma conta? <a href="{{ route('register') }}">Cadastre-se grátis</a></p>
            </div>

            <div style="text-align: center; margin-top: var(--space-lg);">
                <button class="theme-toggle">🌙</button>
            </div>
        </div>
    </div>

    @if(config('app.env') === 'local')
        <script src="{{ asset('js/app.js') }}"></script>
    @else
        <script src="{{ asset('js/app.min.js') }}"></script>
    @endif
</body>
</html>
