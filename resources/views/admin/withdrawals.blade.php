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
                                    <span class="badge badge-success">Pago</span>
                                @elseif($withdrawal->status === 'pending')
                                    <span class="badge badge-warning">Pendente</span>
                                @else
                                    <span class="badge badge-danger">Rejeitado</span>
                                @endif
                            </td>
                            <td>
                                @if($withdrawal->status === 'pending')
                                    <div style="display: flex; gap: 8px;">
                                        <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST" onsubmit="return confirm('Confirmar que você efetuou a transferência PIX de R$ {{ number_format($withdrawal->amount, 2, ',', '.') }} para este criador?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">✓ Confirmar Pago</button>
                                        </form>

                                        <button onclick="openRejectModal('{{ $withdrawal->id }}', '{{ $withdrawal->user->name }}', '{{ number_format($withdrawal->amount, 2, ',', '.') }}')" class="btn btn-danger btn-sm">✕ Rejeitar</button>
                                    </div>
                                @else
                                    <span style="font-size: 0.85rem; color: var(--text-tertiary);">
                                        {{ $withdrawal->status_message ?? 'Sem observações' }}
                                    </span>
                                @endif
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

    {{-- Reject Withdrawal Modal --}}
    <div id="reject-modal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(0,0,0,0.85); backdrop-filter: blur(12px); z-index: 100000; justify-content: center; align-items: center; padding: 20px;">
        <div class="modal-card" style="background: var(--bg-secondary); border: 1px solid rgba(255,255,255,0.08); border-radius: var(--radius-lg); width: 100%; max-width: 440px; box-shadow: var(--shadow-xl); overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-primary); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
                <h3 style="margin: 0; font-size: 1.2rem; font-weight: 700; color: var(--text-primary);">Rejeitar Solicitação de Saque</h3>
                <button onclick="closeRejectModal()" style="background: none; border: none; color: var(--text-tertiary); font-size: 1.5rem; cursor: pointer;">✕</button>
            </div>

            <form id="reject-form" method="POST" action="" style="padding: 24px;">
                @csrf
                <p style="margin-top: 0; font-size: 0.9rem; color: var(--text-secondary); line-height: 1.5;">
                    Rejeitar o saque de <strong id="reject-modal-user"></strong> no valor de <strong id="reject-modal-amount"></strong>. O saldo será devolvido à carteira do criador.
                </p>

                <div style="margin-bottom: var(--space-lg);">
                    <label class="form-label" style="display: block; margin-bottom: 6px; font-size: 0.85rem;">Motivo da Rejeição</label>
                    <textarea name="reason" class="form-input" rows="4" required placeholder="Ex: Chave PIX inválida, dados inconsistentes..." style="resize: none;"></textarea>
                </div>

                <div style="display: flex; gap: var(--space-md); justify-content: flex-end;">
                    <button type="button" onclick="closeRejectModal()" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Rejeição</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(id, user, amount) {
            const modal = document.getElementById('reject-modal');
            const form = document.getElementById('reject-form');
            document.getElementById('reject-modal-user').textContent = user;
            document.getElementById('reject-modal-amount').textContent = 'R$ ' + amount;
            form.action = `/admin/withdrawals/${id}/reject`;
            modal.style.display = 'flex';
        }

        function closeRejectModal() {
            const modal = document.getElementById('reject-modal');
            modal.style.display = 'none';
        }
    </script>
@endsection
