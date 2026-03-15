@extends('layouts.app')

@section('title', 'Nouvelle réservation')

@section('content')
<section class="py-8 lg:py-12 min-h-screen" style="background: var(--sm-slate);">
  <div class="container mx-auto px-6 lg:px-20 max-w-2xl">
    <nav class="flex items-center gap-2 text-sm mb-6" style="color: var(--sm-muted);" aria-label="Fil d'Ariane">
      <a href="{{ url('/services') }}" class="hover:underline" style="color: var(--sm-primary);">Réserver un service</a>
      <span aria-hidden="true">/</span>
      <span style="color: var(--sm-text);">Nouvelle réservation</span>
    </nav>
    <h1 class="text-2xl lg:text-3xl font-extrabold mb-1" style="color: var(--sm-text);">Nouvelle réservation</h1>
    <p class="text-sm mb-8" style="color: var(--sm-muted);">Choisissez le service, le type de demande et l'adresse d'intervention.</p>

    @if($errors->any())
      <div class="auth-errors mb-6">
        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
      </div>
    @endif

    @php
      $prestataireId = request('provider') ?? request('prestataire');
      $categoryId = request('category');
      $prestataire = $prestataireId ? \App\Models\Prestataire::with('user', 'services')->find($prestataireId) : null;
      $servicesToShow = $prestataire ? ($categoryId ? $prestataire->services->where('category_id', $categoryId) : $prestataire->services) : collect();
    @endphp

    @if(!$prestataire)
      <div class="bg-white rounded-2xl border p-6 mb-6" style="border-color: var(--sm-border);">
        <p class="mb-4" style="color: var(--sm-text);">Choisissez d'abord un prestataire depuis la recherche de services.</p>
        <a href="{{ url('/services') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold py-2 px-3 rounded-lg text-white" style="background: var(--sm-primary);">Rechercher un service</a>
      </div>
    @else
      <div class="bg-white rounded-2xl border shadow-sm overflow-hidden" style="border-color: var(--sm-border);">
        <div class="p-6 border-b flex flex-wrap items-center justify-between gap-4" style="border-color: var(--sm-border);">
          <div>
            <p class="text-sm font-semibold mb-1" style="color: var(--sm-muted);">Prestataire</p>
            <p class="text-lg font-bold" style="color: var(--sm-text);">{{ $prestataire->user?->name ?? 'Prestataire' }}</p>
          </div>
          <a href="{{ url('/services') }}{{ $categoryId ? '?category='.$categoryId : '' }}" class="text-sm font-semibold px-3 py-2 rounded-lg border shrink-0" style="border-color: var(--sm-border); color: var(--sm-text);">Changer</a>
        </div>
        <form action="{{ route('reservations.store') }}" method="POST" class="p-6 space-y-5">
          @csrf
          <input type="hidden" name="prestataire_id" value="{{ $prestataire->id }}">

          <div class="auth-field">
            <span class="auth-label block mb-2">Service</span>
            <select name="service_id" required class="auth-input">
              <option value="">Choisir un service</option>
              @foreach($servicesToShow as $svc)
                <option value="{{ $svc->id }}" {{ old('service_id') == $svc->id ? 'selected' : '' }}>
                  {{ $svc->name }} @if($svc->price !== null) — {{ number_format($svc->price, 2, ',', ' ') }} {{ config('app.currency_symbol', 'MAD') }} @endif
                </option>
              @endforeach
            </select>
            @if($servicesToShow->isEmpty())
              <p class="text-sm mt-1 text-amber-600">Aucun service dans cette catégorie. <a href="{{ url('/services') }}" class="underline">Choisir un autre prestataire</a>.</p>
            @endif
          </div>

          <div class="auth-field">
            <span class="auth-label block mb-2">Type de demande</span>
            <div class="flex gap-4">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="type_demande" value="immediate" {{ old('type_demande', request('type', 'immediate')) === 'immediate' ? 'checked' : '' }} class="text-teal-600 focus:ring-teal-500">
                <span style="color: var(--sm-text);">Immédiate</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="type_demande" value="programmee" {{ old('type_demande') === 'programmee' ? 'checked' : '' }} class="text-teal-600 focus:ring-teal-500">
                <span style="color: var(--sm-text);">Programmée</span>
              </label>
            </div>
          </div>

          <div class="auth-field">
            <label for="date_prevue" class="auth-label">Date et heure (pour programmée)</label>
            <input type="datetime-local" name="date_prevue" id="date_prevue" value="{{ old('date_prevue') }}"
                   min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                   class="auth-input">
          </div>

          <div class="auth-field">
            <label for="adresse_intervention" class="auth-label">Adresse d'intervention</label>
            <input type="text" name="adresse_intervention" id="adresse_intervention" value="{{ old('adresse_intervention') }}" placeholder="Ex: 12 rue Example, Ville"
                   class="auth-input">
          </div>

          <button type="submit" class="auth-submit w-full" @if($servicesToShow->isEmpty()) disabled @endif>Envoyer la demande</button>
        </form>
      </div>
    @endif

    <p class="mt-6 flex flex-wrap gap-3">
      <a href="{{ url('/services') }}{{ $categoryId ? '?category='.$categoryId : '' }}" class="text-sm font-medium hover:underline" style="color: var(--sm-primary);">← Réserver un autre service</a>
      <a href="{{ route('reservations.index') }}" class="text-sm font-medium hover:underline" style="color: var(--sm-muted);">Mes réservations</a>
    </p>
  </div>
</section>
@endsection
