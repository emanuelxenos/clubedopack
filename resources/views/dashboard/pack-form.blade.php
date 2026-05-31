@extends('layouts.dashboard')

@section('title', isset($pack) ? 'Editar Pack' : 'Novo Pack')

@section('dashboard-content')
    <h1 style="margin-bottom: var(--space-xl);">{{ isset($pack) ? '✏️ Editar Pack' : '📦 Novo Pack' }}</h1>

    @if($errors->any())
        <div style="margin-bottom: var(--space-lg);">
            @foreach($errors->all() as $error)
                <div class="toast toast-error" style="position:static;min-width:auto;margin-bottom:var(--space-xs);">
                    <span>✕</span> <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <form action="{{ isset($pack) ? route('dashboard.packs.update', $pack) : route('dashboard.packs.store') }}"
          method="POST" enctype="multipart/form-data" style="max-width: 700px;">
        @csrf
        @if(isset($pack))
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="title">Título do Pack *</label>
            <input type="text" id="title" name="title" class="form-input"
                   placeholder="Ex: Ensaio Verão 2024" value="{{ old('title', $pack->title ?? '') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Descrição</label>
            <textarea id="description" name="description" class="form-textarea"
                      placeholder="Descreva o conteúdo do seu pack...">{{ old('description', $pack->description ?? '') }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
            <div class="form-group">
                <label class="form-label" for="category_id">Categoria *</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $pack->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="price">Preço (R$) *</label>
                <input type="number" id="price" name="price" class="form-input"
                       placeholder="49.90" step="0.01" min="1" value="{{ old('price', $pack->price ?? '') }}" required>
            </div>
        </div>

        @if(isset($pack))
            <div class="form-group">
                <label class="form-label">Status</label>
                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $pack->is_active) ? 'checked' : '' }}
                           style="accent-color: var(--accent-primary); width: 18px; height: 18px;">
                    <label for="is_active" style="cursor: pointer; color: var(--text-secondary);">Pack ativo (visível no marketplace)</label>
                </div>
            </div>
        @endif

        {{-- Cover Image --}}
        <div class="form-group">
            <label class="form-label">Imagem de Capa</label>
            @if(isset($pack) && $pack->cover_image_path)
                <div style="margin-bottom: var(--space-md);">
                    <img src="{{ $pack->cover_url }}" style="width:200px;height:auto;border-radius:var(--radius-md);">
                </div>
            @endif
            <div class="upload-zone">
                <input type="file" name="cover_image" accept="image/*" style="display:none;">
                <div class="upload-icon">🖼️</div>
                <p>Clique ou arraste a imagem de capa aqui</p>
                <div class="upload-hint">JPG, PNG ou WebP. Máximo 5MB.</div>
            </div>
            <div class="upload-preview"></div>
        </div>

        {{-- Media Files --}}
        <div class="form-group">
            <label class="form-label">Arquivos do Pack (Fotos e Vídeos)</label>

            @if(isset($pack) && $pack->media->count())
                <div style="margin-bottom: var(--space-lg);">
                    <p style="font-size:0.85rem; color:var(--text-tertiary); margin-bottom:var(--space-sm);">Arquivos atuais:</p>
                    <div class="upload-preview">
                        @foreach($pack->media as $media)
                            <div class="upload-preview-item">
                                @if($media->isImage())
                                    <img src="{{ $media->url }}" alt="Media">
                                @else
                                    <div class="placeholder-image">🎬</div>
                                @endif
                                <form action="{{ route('dashboard.media.destroy', $media) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-btn" data-confirm="Remover este arquivo?">✕</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="upload-zone">
                <input type="file" name="media_files[]" accept="image/*,video/*" multiple style="display:none;">
                <div class="upload-icon">📎</div>
                <p>Clique ou arraste seus arquivos aqui</p>
                <div class="upload-hint">Imagens (JPG, PNG, WebP) e Vídeos (MP4, MOV). Múltiplos arquivos permitidos.</div>
            </div>
            <div class="upload-preview"></div>
        </div>

        <div style="display: flex; gap: var(--space-md); margin-top: var(--space-xl);">
            <button type="submit" class="btn btn-primary btn-lg">
                {{ isset($pack) ? '💾 Salvar Alterações' : '🚀 Publicar Pack' }}
            </button>
            <a href="{{ route('dashboard.packs') }}" class="btn btn-secondary btn-lg">Cancelar</a>
        </div>
    </form>
@endsection
