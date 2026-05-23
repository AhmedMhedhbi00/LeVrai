@extends('admin.layout')
@section('title', 'Ordini')
@section('page-title', 'Gestione Ordini')

@section('content')

{{-- TOOLBAR --}}
<div class="adm-toolbar">
  <form method="GET" action="{{ route('admin.ordini') }}" style="display:contents">
    <div class="adm-search">
      <i class="fas fa-search"></i>
      <input type="text" name="q" placeholder="Cerca per email o nome..." value="{{ request('q') }}">
    </div>
    <select name="stato" class="adm-select" style="width:auto">
      <option value="">Tutti gli stati</option>
      @foreach(['pending','pagato','spedito','completato','annullato'] as $stato)
        <option value="{{ $stato }}" {{ request('stato') === $stato ? 'selected' : '' }}>
          {{ ucfirst($stato) }}
        </option>
      @endforeach
    </select>
    <button type="submit" class="adm-btn adm-btn-outline"><i class="fas fa-filter"></i> Filtra</button>
    @if(request()->hasAny(['q','stato']))
      <a href="{{ route('admin.ordini') }}" class="adm-btn adm-btn-outline"><i class="fas fa-times"></i> Reset</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-card-header">
    <span class="adm-card-title">
      <i class="fas fa-shopping-bag" style="color:var(--blue)"></i>
      Ordini
      <span style="font-weight:400;color:var(--text-muted);font-size:.8rem">({{ $ordini->total() }} totali)</span>
    </span>
  </div>
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Cliente</th>
          <th>Totale</th>
          <th>Metodo</th>
          <th>Stato</th>
          <th>Data</th>
          <th>Aggiorna stato</th>
        </tr>
      </thead>
      <tbody>
        @forelse($ordini as $ordine)
        <tr id="ordine-row-{{ $ordine->id }}">
          <td><strong>#{{ $ordine->id }}</strong></td>
          <td>
            <strong style="font-size:.85rem">{{ $ordine->user?->name ?? 'N/D' }}</strong><br>
            <small style="color:var(--text-muted)">{{ $ordine->user?->email }}</small>
          </td>
          <td><strong>€{{ number_format($ordine->totale, 2, ',', '.') }}</strong></td>
          <td>
            <span class="adm-badge gray" style="font-size:.6rem">
              {{ $ordine->metodo_pagamento ?? 'N/D' }}
            </span>
          </td>
          <td>
            @php
              $badge = match($ordine->stato) {
                'pagato'     => 'ok',
                'spedito'    => 'blue',
                'completato' => 'purple',
                'annullato'  => 'danger',
                default      => 'warn',
              };
            @endphp
            <span class="adm-badge {{ $badge }}" id="badge-{{ $ordine->id }}">
              {{ ucfirst($ordine->stato) }}
            </span>
          </td>
          <td style="color:var(--text-muted);font-size:.78rem">
            {{ $ordine->created_at->format('d/m/Y') }}<br>
            <small>{{ $ordine->created_at->format('H:i') }}</small>
          </td>
          <td>
            <div style="display:flex;gap:.4rem;align-items:center">
              <select class="adm-select" id="stato-{{ $ordine->id }}" style="font-size:.75rem;padding:.3rem .5rem">
                @foreach(['pending','pagato','spedito','completato','annullato'] as $s)
                  <option value="{{ $s }}" {{ $ordine->stato === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                  </option>
                @endforeach
              </select>
              <button
                onclick="aggiornaOrdine({{ $ordine->id }})"
                class="adm-btn adm-btn-primary adm-btn-sm">
                <i class="fas fa-check"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted)">
            <i class="fas fa-shopping-bag" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
            Nessun ordine trovato
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($ordini->hasPages())
  <div style="padding:.75rem 1.5rem;border-top:1px solid var(--border)">
    <div class="adm-pagination">
      @if($ordini->onFirstPage())
        <span style="opacity:.4"><i class="fas fa-chevron-left"></i></span>
      @else
        <a href="{{ $ordini->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
      @endif
      @foreach($ordini->getUrlRange(1, $ordini->lastPage()) as $page => $url)
        @if($page == $ordini->currentPage())
          <span class="current">{{ $page }}</span>
        @else
          <a href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach
      @if($ordini->hasMorePages())
        <a href="{{ $ordini->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
      @else
        <span style="opacity:.4"><i class="fas fa-chevron-right"></i></span>
      @endif
    </div>
  </div>
  @endif
</div>

@push('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

function aggiornaOrdine(id){
  const stato = document.getElementById('stato-' + id).value;
  fetch(`/admin/ordini/${id}/stato`, {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrf},
    body: JSON.stringify({ stato })
  })
  .then(r => r.json())
  .then(data => {
    const badgeMap = {
      pending:    ['warn',   'Pending'],
      pagato:     ['ok',     'Pagato'],
      spedito:    ['blue',   'Spedito'],
      completato: ['purple', 'Completato'],
      annullato:  ['danger', 'Annullato'],
    };
    const badge = document.getElementById('badge-' + id);
    const [cls, label] = badgeMap[data.stato] || ['gray', data.stato];
    badge.className = 'adm-badge ' + cls;
    badge.textContent = label;
  })
  .catch(() => alert('Errore aggiornamento stato'));
}
</script>
@endpush

@endsection
