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
            <img id="avatar-preview-img" src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 style="width:100px;height:100px;border-radius:var(--radius-full);object-fit:cover;border:3px solid var(--border-primary);margin-bottom:var(--space-md);">
            <div>
                <label class="btn btn-secondary btn-sm" style="cursor:pointer;">
                    📷 Alterar Avatar
                    <input type="file" name="avatar" accept="image/*" style="display:none;" onchange="previewProfileAvatar(this)">
                </label>
                <span class="avatar-name" style="font-size:0.8rem;color:var(--text-tertiary);margin-left:var(--space-sm);"></span>
            </div>
        </div>

        <script>
            function previewProfileAvatar(input) {
                const file = input.files[0];
                if (file) {
                    if (file.size > 2048 * 1024) {
                        alert('⚠️ Atenção: Este arquivo tem ' + (file.size / (1024 * 1024)).toFixed(2) + 'MB. O limite máximo permitido para o avatar é de 2MB. Selecione uma imagem menor.');
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('avatar-preview-img').src = e.target.result;
                        document.querySelector('.avatar-name').textContent = file.name;
                    }
                    reader.readAsDataURL(file);
                }
            }
        </script>

        {{-- Banner --}}
        <div class="form-group">
            <label class="form-label">Banner do Perfil</label>
            <div class="banner-preview-container" style="margin-bottom:var(--space-sm); border-radius:var(--radius-md); overflow:hidden; height:120px; background: var(--bg-tertiary); display: {{ $user->banner_url ? 'block' : 'none' }}; border: 1px solid var(--border-primary);">
                <img id="banner-preview-img" src="{{ $user->banner_url }}" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div class="upload-zone" style="padding:var(--space-lg); cursor: pointer;">
                <input type="file" name="banner" id="banner-input" accept="image/*" style="display:none;" onchange="previewProfileBanner(this)">
                <p>Clique para alterar o banner</p>
                <div class="upload-hint">Tamanho máximo: 5MB (Recomendado: 1200x400px)</div>
            </div>
        </div>

        <script>
            function previewProfileBanner(input) {
                const file = input.files[0];
                if (file) {
                    // Validação prévia de tamanho no front-end para evitar sensação de bug
                    if (file.size > 5120 * 1024) {
                        alert('⚠️ Atenção: Este arquivo tem ' + (file.size / (1024 * 1024)).toFixed(2) + 'MB. O limite máximo permitido para o banner é de 5MB. Por favor, selecione uma imagem menor para evitar erros ao salvar.');
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const container = document.querySelector('.banner-preview-container');
                        const img = document.getElementById('banner-preview-img');
                        img.src = e.target.result;
                        container.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            }
        </script>

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

        {{-- Dados de Repasse (Pix) --}}
        <div style="margin-top: var(--space-xl); margin-bottom: var(--space-xl); padding: var(--space-md); background: rgba(233, 30, 140, 0.03); border: 1px dashed rgba(233, 30, 140, 0.2); border-radius: var(--radius-md);">
            <h4 style="margin: 0 0 var(--space-sm) 0; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                💸 Configurações de Repasse (Pix)
            </h4>
            <p style="color: var(--text-secondary); font-size: 0.8rem; margin: 0 0 var(--space-md) 0;">
                Cadastre sua chave Pix para receber os pagamentos instantâneos das suas vendas diretamente na sua conta bancária.
            </p>

            <div class="form-group">
                <label class="form-label" for="pix_key_type">Tipo de Chave Pix</label>
                <select id="pix_key_type" name="pix_key_type" class="form-input" style="background: var(--bg-tertiary);">
                    <option value="" {{ old('pix_key_type', $user->pix_key_type) == '' ? 'selected' : '' }}>Selecione um tipo...</option>
                    <option value="cpf" {{ old('pix_key_type', $user->pix_key_type) == 'cpf' ? 'selected' : '' }}>CPF</option>
                    <option value="email" {{ old('pix_key_type', $user->pix_key_type) == 'email' ? 'selected' : '' }}>E-mail</option>
                    <option value="phone" {{ old('pix_key_type', $user->pix_key_type) == 'phone' ? 'selected' : '' }}>Celular</option>
                    <option value="random" {{ old('pix_key_type', $user->pix_key_type) == 'random' ? 'selected' : '' }}>Chave Aleatória</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="pix_key">Sua Chave Pix</label>
                <input type="text" id="pix_key" name="pix_key" class="form-input" 
                       value="{{ old('pix_key', $user->pix_key) }}" 
                       placeholder="Insira sua chave Pix aqui...">
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg mt-lg">💾 Salvar Perfil</button>
    </form>
@endsection
