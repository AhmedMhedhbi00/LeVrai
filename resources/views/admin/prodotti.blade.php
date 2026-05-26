@extends('admin.layout')
@section('title', 'Prodotti')
@section('page-title', 'Gestione Prodotti')

@section('content')

<div class="adm-toolbar">
  <form method="GET" action="{{ route('admin.prodotti') }}" style="display:contents">
    <div class="adm-search">
      <i class="fas fa-search"></i>
      <input type="text" name="q" placeholder="Cerca prodotto, brand..." value="{{ request('q') }}">
    </div>
    <select name="categoria" class="adm-select" style="width:auto">
      <option value="">Tutte le categorie</option>
      @foreach($categorie as $cat)
        <option value="{{ $cat }}" {{ request('categoria') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
      @endforeach
    </select>
    <select name="stato" class="adm-select" style="width:auto">
      <option value="">Tutti gli stati</option>
      <option value="disponibile" {{ request('stato')==='disponibile'?'selected':'' }}>Disponibile</option>
      <option value="esaurito"    {{ request('stato')==='esaurito'   ?'selected':'' }}>Esaurito</option>
      <option value="scontato"    {{ request('stato')==='scontato'   ?'selected':'' }}>In sconto</option>
    </select>
    <select name="sort" class="adm-select" style="width:auto">
      <option value="id"       {{ request('sort','id')==='id'      ?'selected':'' }}>Più recenti</option>
      <option value="nome"     {{ request('sort')==='nome'         ?'selected':'' }}>Nome A→Z</option>
      <option value="prezzo"   {{ request('sort')==='prezzo'       ?'selected':'' }}>Prezzo</option>
      <option value="quantita" {{ request('sort')==='quantita'     ?'selected':'' }}>Quantità</option>
    </select>
    <button type="submit" class="adm-btn adm-btn-outline"><i class="fas fa-filter"></i> Filtra</button>
    @if(request()->hasAny(['q','categoria','stato','sort']))
      <a href="{{ route('admin.prodotti') }}" class="adm-btn adm-btn-outline"><i class="fas fa-times"></i> Reset</a>
    @endif
  </form>
  <div style="margin-left:auto">
    <a href="{{ route('admin.prodotti.crea') }}" class="adm-btn adm-btn-primary">
      <i class="fas fa-plus"></i> Nuovo prodotto
    </a>
  </div>
</div>

{{-- BARRA ELIMINAZIONE MASSIVA (appare solo quando selezioni) --}}
<div id="bulk-bar" style="display:none;background:#fff1f2;border:1px solid #fca5a5;border-radius:8px;padding:.85rem 1.25rem;margin-bottom:1rem;display:none;align-items:center;gap:1rem;flex-wrap:wrap">
  <span style="font-size:.85rem;font-weight:600;color:#991b1b">
    <i class="fas fa-check-square"></i>
    <span id="bulk-count">0</span> prodotti selezionati
  </span>
  <form method="POST" action="{{ route('admin.prodotti.eliminaMassivo') }}" id="bulk-form">
    @csrf
    <div id="bulk-inputs"></div>
    <button type="button" onclick="confirmBulkDelete()"
      class="adm-btn adm-btn-danger adm-btn-sm">
      <i class="fas fa-trash"></i> Elimina selezionati
    </button>
  </form>
  <button onclick="deselectAll()" class="adm-btn adm-btn-outline adm-btn-sm">
    <i class="fas fa-times"></i> Deseleziona tutto
  </button>
</div>

<div class="adm-card">
  <div class="adm-card-header">
    <span class="adm-card-title">
      <i class="fas fa-tshirt" style="color:var(--blue)"></i> Prodotti
      <span style="font-weight:400;color:var(--text-muted);font-size:.8rem">({{ $prodotti->total() }} totali)</span>
    </span>
  </div>
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th width="40">
            <input type="checkbox" id="select-all" title="Seleziona tutti"
              style="width:16px;height:16px;cursor:pointer" onchange="toggleAll(this)">
          </th>
          <th width="50">#</th>
          <th width="60">Img</th>
          <th>Nome</th>
          <th>Brand</th>
          <th>Categoria</th>
          <th>Prezzo</th>
          <th>Sconto</th>
          <th>Taglie</th>
          <th>Quantità</th>
          <th>Stato</th>
          <th width="110">Azioni</th>
        </tr>
      </thead>
      <tbody>
        @forelse($prodotti as $p)
        <tr id="row-{{ $p->id }}">
          <td>
            <input type="checkbox" class="row-check" value="{{ $p->id }}"
              style="width:16px;height:16px;cursor:pointer" onchange="updateBulkBar()">
          </td>
          <td style="color:var(--text-muted);font-size:.75rem">{{ $p->id }}</td>
          <td>
            <img src="{{ asset('assets/images/prodotti/'.($p->immagine ?? 'default.jpg')) }}"
                 class="prod-img" alt="{{ $p->nome }}"
                 onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
          </td>
          <td><strong style="font-size:.85rem">{{ $p->nome }}</strong></td>
          <td style="font-size:.82rem;color:var(--text-muted)">{{ $p->brand ?? '—' }}</td>
          <td><span class="adm-badge gray">{{ ucfirst($p->categoria) }}</span></td>
          <td>
            <strong>€{{ number_format($p->prezzo,2,',','.') }}</strong>
            @if($p->prezzo_scontato && $p->prezzo_scontato < $p->prezzo)
              <br><small style="color:var(--blue)">€{{ number_format($p->prezzo_scontato,2,',','.') }}</small>
            @endif
          </td>
          <td>
            @if($p->sconto > 0)
              <span class="adm-badge warn">-{{ $p->sconto }}%</span>
            @else
              <span style="color:var(--text-muted)">—</span>
            @endif
          </td>
          <td style="font-size:.75rem;color:var(--text-muted)">{{ $p->taglie ?? '—' }}</td>
          <td>
            <div class="qty-inline">
              <input type="number" value="{{ $p->quantita }}" min="0" style="width:58px">
              <button class="save-qty" onclick="saveQty({{ $p->id }}, this)">Salva</button>
            </div>
          </td>
          <td>
            @if($p->quantita == 0)
              <span class="adm-badge danger stock-badge">Esaurito</span>
            @elseif($p->quantita <= 5)
              <span class="adm-badge warn stock-badge">{{ $p->quantita }} pz</span>
            @else
              <span class="adm-badge ok stock-badge">{{ $p->quantita }} pz</span>
            @endif
          </td>
          <td>
            <div style="display:flex;gap:4px;align-items:center">
              <a href="{{ route('admin.prodotti.modifica', $p->id) }}"
                 class="adm-btn adm-btn-outline adm-btn-sm adm-btn-icon" title="Modifica">
                <i class="fas fa-edit"></i>
              </a>
              <form method="POST" action="{{ route('admin.prodotti.elimina', $p->id) }}" style="display:inline"
                    onsubmit="return confirm('Eliminare «{{ addslashes($p->nome) }}»? Questa azione è irreversibile.')">
                @csrf
                <button type="submit" class="adm-btn adm-btn-danger adm-btn-sm adm-btn-icon" title="Elimina">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="12" style="text-align:center;padding:3rem;color:var(--text-muted)">
            <i class="fas fa-box-open" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
            Nessun prodotto trovato
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($prodotti->hasPages())
  <div style="padding:.75rem 1.5rem;border-top:1px solid var(--border)">
    <div class="adm-pagination">
      @if($prodotti->onFirstPage())
        <span style="opacity:.4"><i class="fas fa-chevron-left"></i></span>
      @else
        <a href="{{ $prodotti->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
      @endif
      @foreach($prodotti->getUrlRange(1, $prodotti->lastPage()) as $page => $url)
        @if($page == $prodotti->currentPage())
          <span class="current">{{ $page }}</span>
        @else
          <a href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach
      @if($prodotti->hasMorePages())
        <a href="{{ $prodotti->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
      @else
        <span style="opacity:.4"><i class="fas fa-chevron-right"></i></span>
      @endif
    </div>
  </div>
  @endif
</div>

@push('scripts')
<script>
function toggleAll(cb) {
  document.querySelectorAll('.row-check').forEach(c => c.checked = cb.checked);
  updateBulkBar();
}

function deselectAll() {
  document.querySelectorAll('.row-check').forEach(c => c.checked = false);
  document.getElementById('select-all').checked = false;
  updateBulkBar();
}

function updateBulkBar() {
  const checked = document.querySelectorAll('.row-check:checked');
  const bar = document.getElementById('bulk-bar');
  document.getElementById('bulk-count').textContent = checked.length;

  // Aggiorna gli input nascosti nel form
  const container = document.getElementById('bulk-inputs');
  container.innerHTML = '';
  checked.forEach(c => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'ids[]';
    input.value = c.value;
    container.appendChild(input);
  });

  bar.style.display = checked.length > 0 ? 'flex' : 'none';

  // Aggiorna checkbox "seleziona tutti"
  const all = document.querySelectorAll('.row-check');
  document.getElementById('select-all').indeterminate = checked.length > 0 && checked.length < all.length;
  document.getElementById('select-all').checked = checked.length === all.length;
}

function confirmBulkDelete() {
  const count = document.querySelectorAll('.row-check:checked').length;
  if (count === 0) return;
  if (confirm(`Sei sicuro di voler eliminare ${count} prodotti? L'azione non è reversibile.`)) {
    document.getElementById('bulk-form').submit();
  }
}
</script>
@endpush

@endsection
