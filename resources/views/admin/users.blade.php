@extends('layouts.admin')

@section('title', 'Admin - Usuários')

@section('admin-content')
    <div class="section-header">
        <h1>👥 Gerenciar Usuários</h1>
    </div>

    {{-- Filters --}}
    <form action="{{ route('admin.users') }}" method="GET" style="display:flex;gap:var(--space-md);margin-bottom:var(--space-xl);flex-wrap:wrap;">
        <input type="text" name="search" class="form-input" placeholder="Buscar por nome, email..." value="{{ request('search') }}" style="max-width:300px;">
        <select name="role" class="form-select" style="max-width:200px;" onchange="this.form.submit()">
            <option value="">Todos os tipos</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="creator" {{ request('role') === 'creator' ? 'selected' : '' }}>Criador</option>
            <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Cliente</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filtrar</button>
    </form>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>E-mail</th>
                    <th>Username</th>
                    <th>Tipo</th>
                    <th>Cadastro</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:var(--space-sm);">
                                <img src="{{ $user->avatar_url }}" style="width:36px;height:36px;border-radius:var(--radius-full);object-fit:cover;">
                                <span style="font-weight:600;">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-danger">Admin</span>
                            @elseif($user->role === 'creator')
                                <span class="badge badge-accent">Criador</span>
                            @else
                                <span class="badge badge-info">Cliente</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge badge-success">Ativo</span>
                            @else
                                <span class="badge badge-danger">Inativo</span>
                            @endif
                        </td>
                        <td>
                            @if($user->role !== 'admin')
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                                        {{ $user->is_active ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>
                            @endif
                            @if($user->isCreator())
                                <a href="/{{ $user->username }}" class="btn btn-secondary btn-sm" target="_blank">Ver Perfil</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="pagination">
            {{ $users->appends(request()->query())->links('pagination.custom') }}
        </div>
    @endif
@endsection
