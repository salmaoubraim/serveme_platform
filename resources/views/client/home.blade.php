@extends('layouts.client')

@section('title', 'Accueil')

@section('content')
<div class="p-4 lg:p-8 max-w-4xl mx-auto">
  {{-- En-tête --}}
  <h1 class="text-2xl lg:text-3xl font-extrabold mb-1" style="color: var(--color-text);">Bonjour, {{ auth()->user()->name }}</h1>
  <p class="text-sm mb-8" style="color: var(--color-text-secondary);">Résumé de vos réservations et accès rapides</p>

  {{-- Cartes statistiques --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('client.historique') }}?status=en_attente" class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
      <p class="text-3xl font-extrabold mb-1" style="color: var(--color-primary);">{{ $pending }}</p>
      <p class="text-sm font-semibold" style="color: var(--color-text);">En attente</p>
    </a>
    <a href="{{ route('client.historique') }}?status=accepte" class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
      <p class="text-3xl font-extrabold mb-1" style="color: var(--color-primary-dark);">{{ $inProgress }}</p>
      <p class="text-sm font-semibold" style="color: var(--color-text);">En cours</p>
    </a>
    <a href="{{ route('client.historique') }}" class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
      <p class="text-3xl font-extrabold mb-1" style="color: var(--color-text);">{{ $pending + $inProgress + $completed }}</p>
      <p class="text-sm font-semibold" style="color: var(--color-text);">Total</p>
    </a>
    <a href="{{ route('client.historique') }}?status=termine" class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
      <p class="text-3xl font-extrabold mb-1" style="color: var(--color-success);">{{ $completed }}</p>
      <p class="text-sm font-semibold" style="color: var(--color-text);">Terminées</p>
    </a>
  </div>

  {{-- Dernières réservations --}}
  <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
      <h2 class="text-lg font-bold" style="color: var(--color-text);">Dernières réservations</h2>
      <a href="{{ route('client.reservations.create') }}" class="inline-flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl text-white" style="background: var(--color-primary);">Nouvelle réservation</a>
    </div>
    <div class="divide-y divide-gray-200">
      @forelse($reservations as $r)
        <a href="{{ route('client.reservations.show', $r->id) }}" class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background: var(--color-primary);">
              {{ strtoupper(mb_substr($r->prestataire->user->name ?? 'P', 0, 1)); }}
            </div>
            <div>
              <p class="font-semibold" style="color: var(--color-text);">{{ $r->prestataire->user->name ?? 'Prestataire' }}</p>
              <p class="text-xs" style="color: var(--color-text-secondary);">{{ $r->created_at->format('d/m/Y H:i') }} · {{ $r->type_demande === 'programmee' ? 'Programmée' : 'Immédiate' }}</p>
            </div>
          </div>
          <span class="text-xs font-semibold px-3 py-1 rounded-full
            @if($r->status === 'en_attente') bg-amber-100 text-amber-800
            @elseif($r->status === 'accepte' || $r->status === 'en_route') bg-teal-100 text-teal-800
            @elseif($r->status === 'termine') bg-gray-100 text-gray-700
            @elseif($r->status === 'refuse' || $r->status === 'annule') bg-red-50 text-red-700
            @else bg-gray-100 text-gray-600
            @endif">
            {{ \App\Models\Reservation::statusLabel($r->status) }}
          </span>
        </a>
      @empty
        <div class="px-6 py-12 text-center" style="color: var(--color-text-secondary);">
          <p class="mb-4">Aucune réservation.</p>
          <a href="{{ route('client.search') }}" class="inline-flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl text-white" style="background: var(--color-primary);">Réserver un service</a>
        </div>
      @endforelse
    </div>
    @if($reservations->count() >= 5)
      <div class="px-6 py-3 border-t border-gray-200 text-center">
        <a href="{{ route('client.historique') }}" class="text-sm font-semibold" style="color: var(--color-primary);">Voir tout</a>
      </div>
    @endif
  </div>

  {{-- Accès rapides --}}
  <div class="mt-8 flex flex-wrap gap-4">
    <a href="{{ route('client.search') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm text-white" style="background: var(--color-primary);">Réserver un service</a>
    <a href="{{ route('client.profil') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 font-semibold text-sm" style="color: var(--color-text);">Mon profil</a>
  </div>
</div>
@endsection
