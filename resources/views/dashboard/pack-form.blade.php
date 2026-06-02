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

    <form id="pack-form" action="{{ isset($pack) ? route('dashboard.packs.update', $pack) : route('dashboard.packs.store') }}"
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

    {{-- Tela de Carregamento de Upload com Progresso --}}
    <div id="upload-loader" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.95); z-index: 99999; flex-direction: column; align-items: center; justify-content: center;">
        <div class="loader" style="width: 60px; height: 60px; border: 5px solid rgba(255,255,255,0.1); border-top: 5px solid #e91e8c; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: var(--space-lg);"></div>
        
        <h3 id="upload-status" style="color: #fff; margin: 0 0 8px 0; font-size: 1.5rem;">Preparando arquivos...</h3>
        
        <div style="width: 80%; max-width: 400px; height: 10px; background: rgba(255,255,255,0.1); border-radius: 5px; margin-bottom: 15px; overflow: hidden;">
            <div id="upload-progress-bar" style="width: 0%; height: 100%; background: #e91e8c; transition: width 0.2s;"></div>
        </div>
        
        <p id="upload-percentage" style="color: #e91e8c; font-weight: bold; font-size: 1.2rem; margin: 0 0 15px 0;">0%</p>

        <p style="color: var(--text-tertiary); font-size: 0.95rem; max-width: 400px; text-align: center; line-height: 1.5;">Por favor, não feche esta página. Arquivos pesados podem demorar alguns minutos dependendo da sua conexão.</p>

        <div id="upload-errors" style="display: none; background: rgba(255,0,0,0.1); border: 1px solid red; color: #ff8888; padding: 15px; border-radius: 8px; margin-top: 20px; max-width: 400px; text-align: left; font-size: 0.9rem;"></div>
        <button id="btn-close-error" type="button" class="btn btn-secondary" style="display: none; margin-top: 15px;">Voltar e Corrigir</button>
    </div>

    <style>
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const packForm = document.getElementById('pack-form');
            const uploadLoader = document.getElementById('upload-loader');
            const progressBar = document.getElementById('upload-progress-bar');
            const percentageText = document.getElementById('upload-percentage');
            const statusText = document.getElementById('upload-status');
            const errorBox = document.getElementById('upload-errors');
            const btnCloseError = document.getElementById('btn-close-error');

            if (packForm) {
                packForm.addEventListener('submit', (e) => {
                    e.preventDefault(); // Impede o envio padrão (recarregamento) para mostrarmos o progresso real via AJAX

                    if (!packForm.checkValidity()) {
                        packForm.reportValidity();
                        return;
                    }

                    // UI Reset
                    uploadLoader.style.display = 'flex';
                    progressBar.style.width = '0%';
                    percentageText.innerText = '0%';
                    statusText.innerText = 'Enviando arquivos...';
                    errorBox.style.display = 'none';
                    errorBox.innerHTML = '';
                    btnCloseError.style.display = 'none';

                    const formData = new FormData(packForm);
                    const xhr = new XMLHttpRequest();

                    xhr.open('POST', packForm.action, true);
                    xhr.setRequestHeader('Accept', 'application/json'); // Pede para o Laravel retornar erro em JSON se algo falhar
                    
                    // Monitorando os bytes enviados da máquina do usuário para o servidor
                    xhr.upload.addEventListener('progress', (event) => {
                        if (event.lengthComputable) {
                            let percentComplete = Math.round((event.loaded / event.total) * 100);
                            progressBar.style.width = percentComplete + '%';
                            percentageText.innerText = percentComplete + '%';
                            
                            if (percentComplete >= 100) {
                                statusText.innerText = 'Processando na Nuvem...';
                            }
                        }
                    });

                    xhr.onload = function() {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            // Laravel Redireciona com sucesso após o Controller
                            window.location.href = '/dashboard/packs';
                        } else if (xhr.status === 422) {
                            // Erro de Validação do Laravel (ex: arquivo maior que o permitido)
                            let response = JSON.parse(xhr.responseText);
                            let errorsHtml = '<b>Erros encontrados:</b><ul style="margin-top:10px; padding-left:20px;">';
                            for (let field in response.errors) {
                                errorsHtml += '<li>' + response.errors[field][0] + '</li>';
                            }
                            errorsHtml += '</ul>';
                            
                            errorBox.innerHTML = errorsHtml;
                            errorBox.style.display = 'block';
                            statusText.innerText = 'Ops! Houve um problema.';
                            btnCloseError.style.display = 'block';
                        } else {
                            // Outro erro de servidor
                            errorBox.innerHTML = 'Erro interno no servidor (' + xhr.status + ').';
                            errorBox.style.display = 'block';
                            statusText.innerText = 'Falha no Upload';
                            btnCloseError.style.display = 'block';
                        }
                    };

                    xhr.onerror = function() {
                        errorBox.innerHTML = 'Erro de conexão. Verifique sua internet.';
                        errorBox.style.display = 'block';
                        statusText.innerText = 'Falha no Upload';
                        btnCloseError.style.display = 'block';
                    };

                    xhr.send(formData);
                });

                btnCloseError.addEventListener('click', () => {
                    uploadLoader.style.display = 'none';
                });
            }
        });
    </script>
@endsection
