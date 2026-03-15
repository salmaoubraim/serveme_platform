@extends('layouts.client')

@section('title', 'Nouvelle réservation')

@section('content')
<div class="p-4 lg:p-8 max-w-2xl mx-auto">
  <nav class="flex items-center gap-2 text-sm mb-6" style="color: var(--color-text-secondary);" aria-label="Fil d'Ariane">
    <a href="{{ route('client.search') }}" class="hover:underline" style="color: var(--color-primary);">Réserver un service</a>
    <span aria-hidden="true">/</span>
    <span style="color: var(--color-text);">Nouvelle réservation</span>
  </nav>
  <h1 class="text-2xl lg:text-3xl font-extrabold mb-1" style="color: var(--color-text);">Nouvelle réservation</h1>
  <p class="text-sm mb-8" style="color: var(--color-text-secondary);">Choisissez le service, le type de demande et l'adresse d'intervention.</p>

  @if($errors->any())
    <div class="mb-6 p-4 rounded-xl text-sm bg-red-50 border border-red-200 text-red-700">
      @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
  @endif

  @if(!$prestataire)
    <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 p-6 mb-6">
      <p class="mb-4" style="color: var(--color-text);">Choisissez d'abord un prestataire depuis la recherche.</p>
      <a href="{{ route('client.search') }}" class="inline-flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl text-white" style="background: var(--color-primary);">Rechercher un prestataire</a>
    </div>
  @else
    <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-sm font-semibold mb-1" style="color: var(--color-text-secondary);">Prestataire</p>
          <p class="text-lg font-bold" style="color: var(--color-text);">{{ $prestataire->user->name ?? 'Prestataire' }}</p>
        </div>
        <a href="{{ route('client.search') }}" class="text-sm font-semibold px-3 py-1.5 rounded-lg border border-gray-300" style="color: var(--color-text);">Changer</a>
      </div>
      <form action="{{ route('reservations.store') }}" method="POST" class="p-6 space-y-5">
        @csrf
        <input type="hidden" name="prestataire_id" value="{{ $prestataire->id }}">

        <div>
          <label class="block text-sm font-medium mb-2" style="color: var(--color-text);">Service</label>
          <select name="service_id" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[var(--color-primary)]">
            <option value="">Choisir un service</option>
            @foreach($prestataire->services as $svc)
              <option value="{{ $svc->id }}" {{ old('service_id') == $svc->id ? 'selected' : '' }}>
                {{ $svc->name }} @if($svc->price !== null) — {{ number_format($svc->price, 2, ',', ' ') }} {{ config('app.currency_symbol', 'MAD') }} @endif
              </option>
            @endforeach
          </select>
          @if($prestataire->services->isEmpty())
            <p class="text-sm mt-1 text-amber-600">Aucun service proposé pour le moment.</p>
          @endif
        </div>

        <div>
          <span class="block text-sm font-medium mb-2" style="color: var(--color-text);">Type de demande</span>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="type_demande" value="immediate" {{ old('type_demande', request('type', 'immediate')) === 'immediate' ? 'checked' : '' }} class="text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
              <span style="color: var(--color-text);">Immédiate</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="type_demande" value="programmee" {{ old('type_demande') === 'programmee' ? 'checked' : '' }} class="text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
              <span style="color: var(--color-text);">Programmée</span>
            </label>
          </div>
        </div>

        <div>
          <label for="date_prevue" class="block text-sm font-medium mb-2" style="color: var(--color-text);">Date et heure (pour programmée)</label>
          <input type="datetime-local" name="date_prevue" id="date_prevue" value="{{ old('date_prevue') }}" min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                 class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[var(--color-primary)]">
        </div>

        <div>
          <label for="adresse_intervention" class="block text-sm font-medium mb-2" style="color: var(--color-text);">Adresse d'intervention</label>
          <input type="text" name="adresse_intervention" id="adresse_intervention" value="{{ old('adresse_intervention') }}" placeholder="Ex: 12 rue Example, Ville"
                 class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[var(--color-primary)]">
        </div>

        <button type="submit" class="w-full py-3 rounded-xl text-white font-bold text-sm" style="background: var(--color-primary);" @if($prestataire->services->isEmpty()) disabled @endif>Envoyer la demande</button>
      </form>
    </div>
  @endif

  <p class="mt-6 flex flex-wrap gap-3">
    <a href="{{ route('client.search') }}" class="text-sm font-medium hover:underline" style="color: var(--color-primary);">← Réserver un autre service</a>
    <a href="{{ route('client.historique') }}" class="text-sm font-medium hover:underline" style="color: var(--color-text-secondary);">Mes réservations</a>
  </p>
</div>
@endsection
