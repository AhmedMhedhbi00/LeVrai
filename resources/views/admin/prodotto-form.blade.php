@extends('admin.layout')
@section('title', $prodotto ? 'Modifica prodotto' : 'Nuovo prodotto')
@section('page-title', $prodotto ? 'Modifica: ' . $prodotto->nome : 'Nuovo prodotto')

@section('content')

<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem;font-size:.8rem;color:var(--text-muted)">
  <a href="{{ route('admin.prodotti') }}" style="color:var(--blue);text-decoration:none">Prodotti</a>
  <i class="fas fa-chevron-right" style="font-size:.6rem"></i>
  <span>{{ $prodotto ? 'Modifica' : 'Nuovo prodotto' }}</span>
</div>

@if($errors->any())
<ul class="adm-errors-list">
  @foreach($errors->all() as $error)
    <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
  @endforeach
</ul>
@endif

{{-- NOTA: action diversa per store (POST) e update (POST con route dedicata) --}}
<form method="POST"
      action="{{ $prodotto ? route('admin.prodotti.update', $prodotto->id) : route('admin.prodotti.store') }}"
      enctype="multipart/form-data">
  @csrf

  <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">

    {{-- SINISTRA --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem">

      <div class="adm-card">
        <div class="adm-card-header">
          <span class="adm-card-title"><i class="fas fa-info-circle" style="color:var(--blue)"></i> Informazioni</span>
        </div>
        <div class="adm-card-body">
          <div class="adm-form-grid">
            <div class="adm-form-group full">
              <label class="adm-label">Nome prodotto *</label>
              <input type="text" name="nome" class="adm-input"
                value="{{ old('nome', $prodotto?->nome) }}" placeholder="Es. Felpa Oversize Nera" required>
              @error('nome')<span class="adm-error">{{ $message }}</span>@enderror
            </div>
            <div class="adm-form-group">
              <label class="adm-label">Brand</label>
              <input type="text" name="brand" class="adm-input"
                value="{{ old('brand', $prodotto?->brand) }}" placeholder="Es. Le Vrai">
              @error('brand')<span class="adm-error">{{ $message }}</span>@enderror
            </div>
            <div class="adm-form-group">
              <label class="adm-label">Categoria *</label>
              <select name="categoria" class="adm-select" required>
                <option value="">— Scegli —</option>
                @foreach($categorie as $cat)
                  <option value="{{ $cat }}" {{ old('categoria', $prodotto?->categoria) === $cat ? 'selected' : '' }}>
                    {{ ucfirst($cat) }}
                  </option>
                @endforeach
              </select>
              @error('categoria')<span class="adm-error">{{ $message }}</span>@enderror
            </div>
          </div>
        </div>
      </div>

      <div class="adm-card">
        <div class="adm-card-header">
          <span class="adm-card-title"><i class="fas fa-euro-sign" style="color:var(--green)"></i> Prezzi</span>
        </div>
        <div class="adm-card-body">
          <div class="adm-form-grid cols3">
            <div class="adm-form-group">
              <label class="adm-label">Prezzo pieno (€) *</label>
              <input type="number" name="prezzo" class="adm-input" step="0.01" min="0"
                id="inp-prezzo" value="{{ old('prezzo', $prodotto?->prezzo) }}" placeholder="0.00" required>
              @error('prezzo')<span class="adm-error">{{ $message }}</span>@enderror
            </div>
            <div class="adm-form-group">
              <label class="adm-label">Sconto (%)</label>
              <input type="number" name="sconto" class="adm-input" min="0" max="100"
                id="inp-sconto" value="{{ old('sconto', $prodotto?->sconto ?? 0) }}" placeholder="0">
              @error('sconto')<span class="adm-error">{{ $message }}</span>@enderror
            </div>
            <div class="adm-form-group">
              <label class="adm-label">Prezzo scontato</label>
              <input type="text" class="adm-input" id="prev-scontato" readonly
                style="background:#f5f5f5;color:var(--text-muted)" placeholder="Auto">
            </div>
          </div>
        </div>
      </div>

      <div class="adm-card">
        <div class="adm-card-header">
          <span class="adm-card-title"><i class="fas fa-boxes" style="color:var(--orange)"></i> Stock e taglie</span>
        </div>
        <div class="adm-card-body">
          <div class="adm-form-grid">
            <div class="adm-form-group">
              <label class="adm-label">Quantità *</label>
              <input type="number" name="quantita" class="adm-input" min="0"
                value="{{ old('quantita', $prodotto?->quantita ?? 0) }}" required>
              @error('quantita')<span class="adm-error">{{ $message }}</span>@enderror
            </div>
            <div class="adm-form-group">
              <label class="adm-label">Taglie (separate da virgola)</label>
              <input type="text" name="taglie" class="adm-input"
                value="{{ old('taglie', $prodotto?->taglie) }}"
                placeholder="S, M, L, XL oppure 40, 41, 42">
              <span class="adm-input-hint">Es: <code>S,M,L,XL</code> oppure <code>40,41,42</code></span>
              @error('taglie')<span class="adm-error">{{ $message }}</span>@enderror
            </div>
          </div>
          <div style="margin-top:1rem">
            <div class="adm-label" style="margin-bottom:.5rem">Preset rapidi</div>
            <div style="display:flex;flex-wrap:wrap;gap:.4rem">
              @foreach(['XS,S,M,L,XL,XXL','S,M,L,XL','XS,S,M,L','36,37,38,39,40,41,42,43,44,45','38,39,40,41,42,43,44','TU'] as $preset)
                <button type="button" class="adm-btn adm-btn-outline adm-btn-sm"
                  onclick="document.querySelector('[name=taglie]').value='{{ $preset }}'">{{ $preset }}</button>
              @endforeach
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- DESTRA: immagine + salva --}}
    <div>
      <div class="adm-card" style="position:sticky;top:70px">
        <div class="adm-card-header">
          <span class="adm-card-title"><i class="fas fa-image" style="color:var(--blue)"></i> Immagine</span>
        </div>
        <div class="adm-card-body">
          @if($prodotto?->immagine && $prodotto->immagine !== 'default.jpg')
            <div style="margin-bottom:1rem">
              <div class="adm-label" style="margin-bottom:.4rem">Immagine attuale</div>
              <img src="{{ asset('assets/images/prodotti/'.$prodotto->immagine) }}"
                   style="width:100%;max-height:220px;object-fit:cover;border-radius:4px;border:1px solid var(--border)"
                   onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
            </div>
          @endif
          <div class="adm-form-group">
            <label class="adm-label">{{ $prodotto ? 'Sostituisci immagine' : 'Carica immagine' }}</label>
            <input type="file" name="immagine" class="adm-input"
              accept="image/jpeg,image/png,image/webp"
              onchange="prevImg(this)">
            <span class="adm-input-hint">JPG, PNG, WebP · max 4MB</span>
            @error('immagine')<span class="adm-error">{{ $message }}</span>@enderror
          </div>
          <img id="new-img-prev" style="display:none;width:100%;max-height:180px;object-fit:cover;border-radius:4px;margin-top:.5rem">
        </div>
        <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);display:flex;flex-direction:column;gap:.6rem">
          <button type="submit" class="adm-btn adm-btn-primary" style="justify-content:center;width:100%">
            <i class="fas fa-save"></i> {{ $prodotto ? 'Aggiorna prodotto' : 'Crea prodotto' }}
          </button>
          <a href="{{ route('admin.prodotti') }}" class="adm-btn adm-btn-outline" style="justify-content:center;width:100%">
            <i class="fas fa-arrow-left"></i> Annulla
          </a>
        </div>
      </div>
    </div>

  </div>
</form>

@push('scripts')
<script>
function prevImg(input){
  const p = document.getElementById('new-img-prev');
  if(input.files&&input.files[0]){
    const r=new FileReader();
    r.onload=e=>{p.src=e.target.result;p.style.display='block'};
    r.readAsDataURL(input.files[0]);
  }
}
const inpP=document.getElementById('inp-prezzo');
const inpS=document.getElementById('inp-sconto');
const prev=document.getElementById('prev-scontato');
function calcScontato(){
  const p=parseFloat(inpP.value)||0;
  const s=parseInt(inpS.value)||0;
  prev.value = (p>0&&s>0) ? '€'+(p*(1-s/100)).toFixed(2).replace('.',',') : '';
}
inpP?.addEventListener('input',calcScontato);
inpS?.addEventListener('input',calcScontato);
calcScontato();
</script>
@endpush

@endsection
