@extends('layouts.dashboard')

@section('title', 'Meus Packs')

@section('dashboard-content')
    <div class="section-header">
        <h1>📦 Meus Packs</h1>
        <a href="{{ route('dashboard.packs.create') }}" class="btn btn-primary">+ Novo Pack</a>
    </div>

    @if($packs->count())
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pack</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Vendas</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packs as $pack)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:var(--space-sm);">
                                    <div style="width:50px;height:50px;border-radius:var(--radius-sm);overflow:hidden;background:var(--bg-tertiary);flex-shrink:0;">
                                        @if($pack->cover_image_path)
                                            <img src="{{ $pack->cover_url }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <div class="placeholder-image" style="font-size:1.2rem;">📸</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:var(--text-primary);">{{ $pack->title }}</div>
                                        <div style="font-size:0.8rem;color:var(--text-tertiary);">{{ $pack->media_count }} arquivos</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $pack->category ? $pack->category->name : '-' }}</td>
                            <td style="font-weight:600;">{{ $pack->formatted_price }}</td>
                            <td>{{ $pack->purchases_count }}</td>
                            <td>{{ $pack->views_count }}</td>
                            <td>
                                @if($pack->is_active)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-danger">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:var(--space-xs);">
                                    <a href="{{ route('dashboard.packs.edit', $pack) }}" class="btn btn-secondary btn-sm">Editar</a>
                                    <a href="{{ route('pack.show', $pack->slug) }}" class="btn btn-secondary btn-sm" target="_blank">Ver</a>
                                    <form action="{{ route('dashboard.packs.destroy', $pack) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Tem certeza que deseja excluir este pack?">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($packs->hasPages())
            <div class="pagination">
                {{ $packs->links('pagination.custom') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h3>Nenhum pack ainda</h3>
            <p>Crie seu primeiro pack e comece a vender!</p>
            <a href="{{ route('dashboard.packs.create') }}" class="btn btn-primary">Criar Primeiro Pack</a>
        </div>
    @endif
@endsection
