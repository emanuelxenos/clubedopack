@extends('layouts.admin')

@section('title', 'Admin - Transações')

@section('admin-content')
    <h1 style="margin-bottom: var(--space-xl);">💳 Transações</h1>

    {{-- Filters --}}
    <form action="{{ route('admin.transactions') }}" method="GET" style="display:flex;gap:var(--space-md);margin-bottom:var(--space-xl);flex-wrap:wrap;">
        <select name="type" class="form-select" style="max-width:200px;" onchange="this.form.submit()">
            <option value="">Todos os tipos</option>
            <option value="purchase" {{ request('type') === 'purchase' ? 'selected' : '' }}>Compras</option>
            <option value="subscription" {{ request('type') === 'subscription' ? 'selected' : '' }}>Assinaturas</option>
        </select>
        <select name="status" class="form-select" style="max-width:200px;" onchange="this.form.submit()">
            <option value="">Todos os status</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluído</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Falhou</option>
        </select>
    </form>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Criador</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Valor Total</th>
                    <th>Taxa Plataforma</th>
                    <th>Criador Recebe</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>#{{ $transaction->id }}</td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $transaction->user->name ?? '-' }}</td>
                        <td>
                            @if($transaction->type === 'purchase')
                                <span class="badge badge-info">Compra</span>
                            @else
                                <span class="badge badge-accent">Assinatura</span>
                            @endif
                        </td>
                        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $transaction->description ?? '-' }}</td>
                        <td style="font-weight:600;">{{ $transaction->formatted_amount }}</td>
                        <td style="color:var(--success);">R$ {{ number_format($transaction->platform_fee, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($transaction->creator_amount, 2, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center;color:var(--text-tertiary);padding:var(--space-2xl);">Nenhuma transação encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        <div class="pagination">
            {{ $transactions->appends(request()->query())->links('pagination.custom') }}
        </div>
    @endif
@endsection
