@extends('layouts.client')

@section('title', 'Réserver un service')

@section('content')
<div class="p-4 lg:p-8 max-w-4xl mx-auto">
  <h1 class="text-2xl lg:text-3xl font-extrabold mb-2" style="color: var(--color-text);">Réserver un service</h1>
  <p class="text-sm mb-8" style="color: var(--color-text-secondary);">Recherchez un prestataire par nom ou catégorie, puis cliquez sur Réserver.</p>

  {{-- Formulaire de recherche --}}
  <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
    <h2 class="text-lg font-bold mb-4" style="color: var(--color-text);">Rechercher un prestataire</h2>
    <form action="{{ route('client.search') }}" method="GET" class="space-y-4">
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="sm:col-span-2 lg:col-span-1">
          <label for="q" class="block text-sm font-medium mb-1" style="color: var(--color-text);">Nom</label>
          <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Ex: Jean Dupont"
                 class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)]">
        </div>
        <div>
          <label for="category" class="block text-sm font-medium mb-1" style="color: var(--color-text);">Catégorie</label>
          <select name="category" id="category" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[var(--color-primary)]">
            <option value="">Toutes</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <span class="block text-sm font-medium mb-2" style="color: var(--color-text);">Type</span>
          <div class="flex gap-3 pt-1">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="type" value="immediate" {{ request('type', 'immediate') === 'immediate' ? 'checked' : '' }} class="text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
              <span class="text-sm" style="color: var(--color-text);">Immédiate</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="type" value="programmee" {{ request('type') === 'programmee' ? 'checked' : '' }} class="text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
              <span class="text-sm" style="color: var(--color-text);">Programmée</span>
            </label>
          </div>
        </div>
      </div>
      <div class="flex flex-wrap gap-3">
        <button type="submit" class="inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl text-white" style="background: var(--color-primary);">Rechercher</button>
        <a href="{{ route('client.search') }}" class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 rounded-xl border border-gray-300" style="color: var(--color-text);">Réinitialiser</a>
      </div>
    </form>
  </div>

  {{-- Résultats --}}
  @if(request()->hasAny(['q', 'category', 'type']))
    <h2 class="text-xl font-bold mb-4" style="color: var(--color-text);">
      {{ $prestataires->isEmpty() ? 'Aucun prestataire trouvé' : $prestataires->count() . ' prestataire(s) trouvé(s)' }}
    </h2>
    @if($prestataires->isEmpty())
      <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 p-8 text-center">
        <p class="mb-4" style="color: var(--color-text-secondary);">Modifiez vos critères de recherche.</p>
        <a href="{{ route('client.search') }}" class="text-sm font-bold" style="color: var(--color-primary);">Réessayer</a>
      </div>
    @else
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($prestataires as $prestataire)
          @php $servicesCount = $prestataire->services->count(); @endphp
          <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-5">
              <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0" style="background: var(--color-primary);">
                  {{ strtoupper(mb_substr($prestataire->user->name ?? 'P', 0, 1)); }}
                </div>
                <div class="min-w-0">
                  <p class="font-bold truncate" style="color: var(--color-text);">{{ $prestataire->user->name ?? 'Prestataire' }}</p>
                  <p class="text-xs" style="color: var(--color-text-secondary);">{{ $servicesCount }} service(s)</p>
                </div>
              </div>
              <div class="flex gap-2 mt-4">
                <a href="{{ route('providers.show', $prestataire->id) }}" class="flex-1 text-center text-sm font-semibold py-2.5 rounded-xl border border-gray-300" style="color: var(--color-text);">Profil</a>
                <a href="{{ route('client.reservations.create') }}?provider={{ $prestataire->id }}&type={{ request('type', 'immediate') }}" class="flex-1 text-center text-sm font-bold py-2.5 rounded-xl text-white" style="background: var(--color-primary);">Réserver</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  @else
    <div class="rounded-2xl p-6 mb-8 border border-gray-200 bg-orange-50/50">
      <p class="mb-4" style="color: var(--color-text);">Utilisez le formulaire ci-dessus pour rechercher un prestataire.</p>
      @if($categories->isNotEmpty())
        <p class="text-sm font-semibold mb-2" style="color: var(--color-text);">Catégories :</p>
        <div class="flex flex-wrap gap-2">
          @foreach($categories as $cat)
            <a href="{{ route('client.search') }}?category={{ $cat->id }}" class="inline-flex px-4 py-2 rounded-xl text-sm font-medium border border-gray-300 bg-white" style="color: var(--color-text);">{{ $cat->name }}</a>
          @endforeach
        </div>
      @endif
    </div>
  @endif
</div>
@endsection
