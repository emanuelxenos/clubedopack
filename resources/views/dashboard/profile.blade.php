@extends('layouts.dashboard')

@section('title', 'Meu Perfil')

@section('dashboard-content')
    <h1 style="margin-bottom: var(--space-2xl);">⚙️ Meu Perfil</h1>

    @if($errors->any())
        <div style="margin-bottom: var(--space-lg);">
            @foreach($errors->all() as $error)
                <div class="toast toast-error" style="position:static;min-width:auto;margin-bottom:var(--space-xs);">
                    <span>✕</span> <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data" style="max-width: 600px;">
        @csrf
        @method('PUT')

        {{-- Avatar --}}
        <div class="form-group" style="text-align:center;">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 style="width:100px;height:100px;border-radius:var(--radius-full);object-fit:cover;border:3px solid var(--border-primary);margin-bottom:var(--space-md);">
            <div>
                <label class="btn btn-secondary btn-sm" style="cursor:pointer;">
                    📷 Alterar Avatar
                    <input type="file" name="avatar" accept="image/*" style="display:none;" onchange="this.form.querySelector('.avatar-name').textContent = this.files[0]?.name || ''">
                </label>
                <span class="avatar-name" style="font-size:0.8rem;color:var(--text-tertiary);margin-left:var(--space-sm);"></span>
            </div>
        </div>

        {{-- Banner --}}
        <div class="form-group">
            <label class="form-label">Banner do Perfil</label>
            @if($user->banner_url)
                <div style="margin-bottom:var(--space-sm);border-radius:var(--radius-md);overflow:hidden;height:100px;">
                    <img src="{{ $user->banner_url }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
            @endif
            <div class="upload-zone" style="padding:var(--space-lg);">
                <input type="file" name="banner" accept="image/*" style="display:none;">
                <p>Clique para alterar o banner</p>
                <div class="upload-hint">Tamanho recomendado: 1200x400px</div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="name">Nome</label>
            <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input type="text" id="username" name="username" class="form-input" value="{{ old('username', $user->username) }}" required>
            <div class="form-hint">Sua URL será: {{ config('app.url') }}/{{ $user->username }}</div>
        </div>

        <div class="form-group">
            <label class="form-label" for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-textarea" placeholder="Conte sobre você e seu conteúdo..."
                      rows="4">{{ old('bio', $user->bio) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label" for="subscription_price">Preço da Assinatura Mensal (R$)</label>
            <input type="number" id="subscription_price" name="subscription_price" class="form-input"
                   step="0.01" min="0" value="{{ old('subscription_price', $user->subscription_price) }}"
                   placeholder="29.90">
            <div class="form-hint">Deixe 0 ou vazio se não quiser oferecer assinatura.</div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg mt-lg">💾 Salvar Perfil</button>
    </form>
@endsection
