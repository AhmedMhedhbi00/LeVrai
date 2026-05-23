@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div class="adm-stats">
  <div class="adm-stat-card">
    <div class="adm-stat-icon blue"><i class="fas fa-tshirt"></i></div>
    <div>
      <div class="adm-stat-num">{{ $stats['prodotti_totali'] }}</div>
      <div class="adm-stat-label">Prodotti totali</div>
    </div>
  </div>
  <div class="adm-stat-card">
    <div class="adm-stat-icon red"><i class="fas fa-times-circle"></i></div>
    <div>
      <div class="adm-stat-num">{{ $stats['prodotti_esauriti'] }}</div>
      <div class="adm-stat-label">Esauriti</div>
    </div>
  </div>
  <div class="adm-stat-card">
    <div class="adm-stat-icon orange"><i class="fas fa-tag"></i></div>
    <div>
      <div class="adm-stat-num">{{ $stats['prodotti_scontati'] }}</div>
      <div class="adm-stat-label">In sconto</div>
    </div>
  </div>
  <div class="adm-stat-card">
    <div class="adm-stat-icon green"><i class="fas fa-shopping-bag"></i></div>
    <div>
      <div class="adm-stat-num">{{ $stats['ordini_totali'] }}</div>
      <div class="adm-stat-label">Ordini totali</div>
    </div>
  </div>
  <div class="adm-stat-card">
    <div class="adm-stat-icon purple"><i class="fas fa-calendar-day"></i></div>
    <div>
      <div class="adm-stat-num">{{ $stats['ordini_oggi'] }}</div>
      <div class="adm-stat-label">Ordini oggi</div>
    </div>
  </div>
  <div class="adm-stat-card">
    <div class="adm-stat-icon teal"><i class="fas fa-euro-sign"></i></div>
    <div>
      <div class="adm-stat-num">€{{ number_format($stats['fatturato_mese'], 0, ',', '.') }}</div>
      <div class="adm-stat-label">Fatturato questo mese</div>
    </div>
  </div>
  <div class="adm-stat-card">
    <div class="adm-stat-icon blue"><i class="fas fa-users"></i></div>
    <div>
      <div class="adm-stat-num">{{ $stats['utenti_totali'] }}</div>
      <div class="adm-stat-label">Utenti registrati</div>
    </div>
  </div>
  <div class="adm-stat-card">
    <div class="adm-stat-icon green"><i class="fas fa-coins"></i></div>
    <div>
      <div class="adm-stat-num">€{{ number_format($stats['fatturato_totale'], 0, ',', '.') }}</div>
      <div class="adm-stat-label">Fatturato totale</div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem">

  {{-- ULTIMI ORDINI --}}
  <div class="adm-card">
    <div class="adm-card-header">
      <span class="adm-card-title"><i class="fas fa-shopping-bag" style="color:var(--blue)"></i> Ultimi
        ordini</span>
      <a href="{{ route('admin.ordini') }}" class="adm-btn adm-btn-outline adm-btn-sm">Vedi tutti</a>
    </div>
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Totale</th>
            <th>Stato</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          @forelse($ultimi_ordini as $ordine)
          <tr>
            <td><strong>#{{ $ordine->id }}</strong></td>
            <td>
              {{ $ordine->user?->name ?? 'N/D' }}<br>
              <small style="color:var(--text-muted)">{{ $ordine->user?->email }}</small>
            </td>
            <td><strong>€{{ number_format($ordine->totale, 2, ',', '.') }}</strong></td>
            <td>
              @php
              $badge = match($ordine->stato) {
              'pagato' => 'ok',
              'spedito' => 'blue',
              'completato' => 'purple',
              'annullato' => 'danger',
              default => 'warn',
              };
              @endphp
              <span class="adm-badge {{ $badge }}">{{ ucfirst($ordine->stato) }}</span>
            </td>
            <td style="color:var(--text-muted);font-size:.78rem">
              {{ $ordine->created_at->format('d/m/Y H:i') }}
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem">Nessun ordine
              ancora</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- PRODOTTI SCORTE BASSE --}}
  <div>
    <div class="adm-card">
      <div class="adm-card-header">
        <span class="adm-card-title"><i class="fas fa-exclamation-triangle" style="color:var(--orange)"></i>
          Scorte basse</span>
      </div>
      <div style="padding:.5rem 0">
        @forelse($prodotti_scarsi as $p)
        <div
          style="display:flex;align-items:center;justify-content:space-between;padding:.75rem 1.25rem;border-bottom:1px solid var(--border)">
          <div>
            <div style="font-size:.82rem;font-weight:600">{{ $p->nome }}</div>
            <div style="font-size:.7rem;color:var(--text-muted)">{{ ucfirst($p->categoria) }}</div>
          </div>
          <span class="adm-badge warn">{{ $p->quantita }} pz</span>
        </div>
        @empty
        <div style="padding:1.5rem;text-align:center;color:var(--text-muted);font-size:.82rem">
          <i class="fas fa-check-circle" style="color:var(--green)"></i> Tutte le scorte sono ok
        </div>
        @endforelse
      </div>
    </div>

    {{-- AZIONI RAPIDE --}}
    <div class="adm-card">
      <div class="adm-card-header">
        <span class="adm-card-title">Azioni rapide</span>
      </div>
      <div class="adm-card-body" style="display:flex;flex-direction:column;gap:.6rem">
        <a href="{{ route('admin.prodotti.crea') }}" class="adm-btn adm-btn-primary"
          style="justify-content:center">
          <i class="fas fa-plus"></i> Nuovo prodotto
        </a>
        <a href="{{ route('admin.ordini') }}" class="adm-btn adm-btn-outline" style="justify-content:center">
          <i class="fas fa-shopping-bag"></i> Gestisci ordini
        </a>
        <a href="{{ route('shop') }}" target="_blank" class="adm-btn adm-btn-outline"
          style="justify-content:center">
          <i class="fas fa-store"></i> Vai allo shop
        </a>
      </div>
    </div>
  </div>

</div>
@endsection