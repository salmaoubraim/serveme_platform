@extends('layouts.app')

@section('title', 'Réserver un service')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@section('content')
<section class="py-8 lg:py-12 min-h-screen" style="background: var(--sm-slate);">
  <div class="container mx-auto px-6 lg:px-20">
    <h1 class="text-2xl lg:text-3xl font-extrabold mb-2" style="color: var(--sm-text);">Réserver un service</h1>
    <p class="text-sm mb-8" style="color: var(--sm-muted);">
      Choisissez une catégorie ou recherchez un prestataire, puis cliquez sur « Réserver » pour envoyer votre demande.
    </p>

    {{-- Formulaire de recherche --}}
    <div class="bg-white rounded-2xl border shadow-sm p-6 mb-8" style="border-color: var(--sm-border);">
      <h2 class="text-lg font-bold mb-4" style="color: var(--sm-text);">Rechercher un prestataire</h2>
      <form action="{{ url('/services') }}" method="GET" class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <div class="sm:col-span-2 lg:col-span-1">
            <label for="q" class="auth-label block mb-1">Nom du prestataire</label>
            <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Ex: Jean Dupont"
                   class="auth-input w-full">
          </div>
          <div>
            <label for="category" class="auth-label block mb-1">Catégorie</label>
            <select name="category" id="category" class="auth-input w-full">
              <option value="">Toutes</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <span class="auth-label block mb-2">Type de demande</span>
            <div class="flex gap-3 pt-1">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="type" value="immediate" {{ request('type', 'immediate') === 'immediate' ? 'checked' : '' }} class="text-teal-600 focus:ring-teal-500">
                <span class="text-sm" style="color: var(--sm-text);">Immédiate</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="type" value="programmee" {{ request('type') === 'programmee' ? 'checked' : '' }} class="text-teal-600 focus:ring-teal-500">
                <span class="text-sm" style="color: var(--sm-text);">Programmée</span>
              </label>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap gap-2 sm:gap-3">
          <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-semibold py-2 px-3 sm:px-4 rounded-lg text-white" style="background: var(--sm-primary);">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Rechercher
          </button>
          <a href="{{ url('/services') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold py-2 px-3 rounded-lg border" style="border-color: var(--sm-border); color: var(--sm-text);">Réinitialiser</a>
        </div>
      </form>
    </div>

    {{-- Résultats (affichés après recherche ou choix de catégorie) --}}
    @if(request()->hasAny(['q', 'category', 'type']))
      <h2 class="text-xl font-bold mb-4" style="color: var(--sm-text);">
        @if($prestataires->isEmpty())
          Aucun prestataire trouvé
        @else
          {{ $prestataires->count() }} prestataire(s) trouvé(s)
        @endif
      </h2>

      @php
        $prestatairesWithCoords = $prestataires->filter(fn($p) => $p->latitude && $p->longitude);
        $mapData = $prestatairesWithCoords->map(fn($p) => [
          'id' => $p->id,
          'name' => $p->user?->name ?? 'Prestataire',
          'lat' => (float) $p->latitude,
          'lng' => (float) $p->longitude,
          'localisation' => $p->localisation ? (string) $p->localisation : '',
        ])->values()->toArray();
      @endphp
      @if(!$prestataires->isEmpty() && $prestatairesWithCoords->isNotEmpty())
        <div class="mb-8 rounded-2xl overflow-hidden border shadow-sm" style="border-color: var(--sm-border);">
          <div class="px-4 py-2 bg-white border-b text-sm font-semibold" style="border-color: var(--sm-border); color: var(--sm-text);">📍 Localisation des prestataires</div>
          <div id="prestataires-map" class="w-full h-64 sm:h-80 bg-slate-100"></div>
        </div>
        @push('scripts')
        <script>
          (function() {
            var prestataires = @json($mapData);
            if (prestataires.length === 0) return;
            function initMap() {
              var L = window.L;
              if (!L) return setTimeout(initMap, 50);
              var center = prestataires.length === 1 ? [prestataires[0].lat, prestataires[0].lng] : [30.4278, -9.5981];
              var map = L.map('prestataires-map').setView(center, prestataires.length === 1 ? 14 : 12);
              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
              function esc(s) { return (s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }
              prestataires.forEach(function(p) {
                var m = L.marker([p.lat, p.lng]).addTo(map);
                m.bindPopup('<strong>' + esc(p.name) + '</strong>' + (p.localisation ? '<br><span class="text-sm text-gray-600">' + esc(p.localisation) + '</span>' : '') + '<br><a href="/providers/' + p.id + '" class="text-teal-600 font-semibold text-sm">Voir le profil</a>');
              });
              if (prestataires.length > 1) map.fitBounds(prestataires.map(function(p) { return [p.lat, p.lng]; }), { padding: [40, 40] });
            }
            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMap); else initMap();
          })();
        </script>
        @endpush
      @endif

      @if($prestataires->isEmpty())
        <div class="bg-white rounded-2xl border p-6 sm:p-8 text-center" style="border-color: var(--sm-border);">
          <p class="mb-4" style="color: var(--sm-muted);">Aucun prestataire ne correspond à vos critères. Modifiez la recherche ou consultez toutes les catégories.</p>
          <a href="{{ url('/services') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold py-2 px-4 rounded-lg text-white" style="background: var(--sm-primary);">Voir tous les prestataires</a>
        </div>
      @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          @foreach($prestataires as $prestataire)
            @php
              $servicesInCategory = $prestataire->services; // déjà filtrés par catégorie côté contrôleur si filtre actif
              $servicesCount = $servicesInCategory->count();
            @endphp
            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden hover:shadow-md transition-shadow" style="border-color: var(--sm-border);">
              <div class="p-5">
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0" style="background: var(--sm-primary);">
                    {{ strtoupper(mb_substr($prestataire->user->name ?? 'P', 0, 1)); }}
                  </div>
                  <div class="min-w-0">
                    <p class="font-bold truncate" style="color: var(--sm-text);">{{ $prestataire->user->name ?? 'Prestataire' }}</p>
                    <p class="text-xs" style="color: var(--sm-muted);">
                      {{ $servicesCount }} service(s){{ request('category') ? ' dans cette catégorie' : '' }}
                      @if($prestataire->note_moyenne > 0)— {{ number_format($prestataire->note_moyenne, 1) }} ★ @endif
                    </p>
                  </div>
                </div>
                <div class="flex gap-2 mt-4 flex-wrap sm:flex-nowrap">
                  <a href="{{ route('providers.show', $prestataire->id) }}{{ request('category') ? '?category='.request('category') : '' }}" class="flex-1 min-w-0 text-center text-sm font-semibold py-2 px-3 rounded-lg border" style="border-color: var(--sm-border); color: var(--sm-text);">Profil</a>
                  <a href="{{ route('reservations.create') }}?provider={{ $prestataire->id }}&type={{ request('type', 'immediate') }}{{ request('category') ? '&category='.request('category') : '' }}" class="flex-1 min-w-0 text-center text-sm font-semibold py-2 px-3 rounded-lg text-white" style="background: var(--sm-primary);">Réserver</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    @else
      {{-- Pas encore de recherche --}}
      <div class="rounded-2xl p-6 mb-8 border max-w-2xl" style="background: var(--sm-light); border-color: var(--sm-accent);">
        <p class="mb-4" style="color: var(--sm-text);">
          Utilisez le formulaire ci-dessus pour rechercher un prestataire par <strong>nom</strong> ou <strong>catégorie</strong>, puis cliquez sur <strong>« Réserver »</strong> pour choisir le service et envoyer votre demande.
        </p>
        @if($categories->isNotEmpty())
          <p class="text-sm font-semibold mb-2" style="color: var(--sm-text);">Ou choisissez une catégorie :</p>
          <div class="flex flex-wrap gap-2">
            @foreach($categories as $cat)
              <a href="{{ url('/services') }}?category={{ $cat->id }}" class="inline-flex items-center gap-1 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm font-medium border transition-colors" style="border-color: var(--sm-border); color: var(--sm-text); background: #fff;">
                {{ $cat->name }}
              </a>
            @endforeach
          </div>
        @endif
      </div>
    @endif
  </div>
</section>
@endsection
