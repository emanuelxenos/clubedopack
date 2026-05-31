@extends('layouts.admin')

@section('title', 'Admin - Categorias')

@section('admin-content')
    <h1 style="margin-bottom: var(--space-xl);">🏷️ Categorias</h1>

    {{-- Add Category Form --}}
    <div class="card" style="padding:var(--space-xl);margin-bottom:var(--space-2xl);max-width:500px;">
        <h3 style="margin-bottom:var(--space-lg);">➕ Nova Categoria</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST" style="display:flex;gap:var(--space-md);flex-wrap:wrap;align-items:flex-end;">
            @csrf
            <div class="form-group" style="margin-bottom:0;flex:1;">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-input" placeholder="Nome da categoria" required>
            </div>
            <div class="form-group" style="margin-bottom:0;width:120px;">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-input" placeholder="slug" required>
            </div>
            <div class="form-group" style="margin-bottom:0;width:80px;">
                <label class="form-label">Ícone</label>
                <input type="text" name="icon" class="form-input" placeholder="🔥">
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>
    </div>

    {{-- Categories List --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ícone</th>
                    <th>Nome</th>
                    <th>Slug</th>
                    <th>Packs</th>
                    <th>Ordem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td style="font-size:1.3rem;">{{ $category->icon }}</td>
                        <td style="font-weight:600;">{{ $category->name }}</td>
                        <td style="color:var(--text-tertiary);">{{ $category->slug }}</td>
                        <td>{{ $category->packs_count }}</td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Excluir categoria '{{ $category->name }}'?">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
