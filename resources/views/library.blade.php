@extends('layouts.app')

@section('title', 'Minha Biblioteca')

@section('content')
<div class="page-content">
    <div class="container">
        <h1 style="margin-bottom: var(--space-xl);">📚 Minha Biblioteca</h1>

        <div class="library-tabs">
            <button class="library-tab active" data-tab="packs" onclick="switchTab('packs')">📦 Packs Comprados</button>
            <button class="library-tab" data-tab="subscriptions" onclick="switchTab('subscriptions')">✨ Assinaturas</button>
        </div>

        {{-- ── Packs Tab ── --}}
        <div class="tab-content" id="tab-packs">
            @if($purchases->count())
                <div class="grid-packs">
                    @foreach($purchases as $purchase)
                        @if($purchase->pack)
                            <a href="{{ route('pack.show', $purchase->pack->slug) }}" class="card pack-card" style="text-decoration:none;">
                                <div class="pack-image">
                                    @if($purchase->pack->cover_image_path)
                                        <img src="{{ $purchase->pack->cover_url }}" alt="{{ $purchase->pack->title }}" loading="lazy">
                                    @else
                                        <div class="placeholder-image">📸</div>
                                    @endif
                                    <span class="pack-badge" style="background:var(--success);">✓ Comprado</span>
                                </div>
                                <div class="pack-info">
                                    <div class="pack-title">{{ $purchase->pack->title }}</div>
                                    <div class="pack-creator">
                                        <img src="{{ $purchase->pack->user->avatar_url }}" alt="{{ $purchase->pack->user->name }}">
                                        <span>{{ $purchase->pack->user->name }}</span>
                                    </div>
                                    <div style="font-size:0.8rem; color:var(--text-tertiary); margin-top:var(--space-xs);">
                                        Comprado em {{ $purchase->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>

                @if($purchases->hasPages())
                    <div class="pagination">
                        {{ $purchases->links('pagination.custom') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>Nenhum pack comprado</h3>
                    <p>Explore o marketplace e encontre packs incríveis dos melhores criadores.</p>
                    <a href="/" class="btn btn-primary">Explorar Packs</a>
                </div>
            @endif
        </div>

        {{-- ── Subscriptions Tab ── --}}
        <div class="tab-content hidden" id="tab-subscriptions">
            @if($subscriptions->count())
                <div class="flex flex-col gap-md">
                    @foreach($subscriptions as $subscription)
                        <div class="subscription-card">
                            <img src="{{ $subscription->creator->avatar_url }}" alt="{{ $subscription->creator->name }}">
                            <div class="sub-info">
                                <a href="/{{ $subscription->creator->username }}" class="sub-name">{{ $subscription->creator->name }}</a>
                                <div class="sub-price">{{ $subscription->formatted_amount }}/mês</div>
                            </div>
                            <div>
                                @if($subscription->isActive())
                                    <span class="badge badge-success">Ativa</span>
                                @else
                                    <span class="badge badge-danger">{{ ucfirst($subscription->status) }}</span>
                                @endif
                                <div class="sub-status" style="color:var(--text-tertiary);margin-top:4px;">
                                    @if($subscription->expires_at)
                                        Expira em {{ $subscription->expires_at->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">✨</div>
                    <h3>Nenhuma assinatura ativa</h3>
                    <p>Assine seus criadores favoritos para ter acesso a todos os seus packs.</p>
                    <a href="/" class="btn btn-primary">Descobrir Criadores</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
