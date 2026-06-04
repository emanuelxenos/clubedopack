@extends('layouts.admin')

@section('title', 'Admin - Saques')

@section('admin-content')
    <div class="section-header">
        <h1>💸 Gerenciar Saques (PIX)</h1>
    </div>

    @if($withdrawals->count())
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Criador</th>
                        <th>Chave PIX</th>
                        <th>Valor</th>
                        <th>Data Solicitação</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $withdrawal)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:var(--space-sm);">
                                    <img src="{{ $withdrawal->user->avatar_url }}" style="width:36px;height:36px;border-radius:var(--radius-full);object-fit:cover;">
                                    <div>
                                        <div style="font-weight:600;">{{ $withdrawal->user->name }}</div>
                                        <div style="font-size:0.8rem;color:var(--text-tertiary);">{{ '@' }}{{ $withdrawal->user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(255,255,255,0.05); font-family: monospace; font-size: 0.85rem;">
                                    {{ strtoupper($withdrawal->pix_key_type) }}: {{ $withdrawal->pix_key }}
                                </span>
                            </td>
                            <td style="font-weight:700; font-size: 1rem;">R$ {{ number_format($withdrawal->amount, 2, ',', '.') }}</td>
                            <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($withdrawal->status === 'completed')
                                    <span class="badge badge-success">✓ Transferido (Automático)</span>
                                @elseif($withdrawal->status === 'pending')
                                    <span class="badge badge-warning">⌛ Processando (Fila)</span>
                                @else
                                    <span class="badge badge-danger">✕ Falhou</span>
                                @endif
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--text-tertiary);">
                                    {{ $withdrawal->status_message ?? 'Processamento automático' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="pagination">
                {{ $withdrawals->links('pagination.custom') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">💸</div>
            <h3>Nenhuma solicitação de saque</h3>
            <p>Os saques solicitados pelos criadores aparecerão aqui.</p>
        </div>
    @endif
@endsection
