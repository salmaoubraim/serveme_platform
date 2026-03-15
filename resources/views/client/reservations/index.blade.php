@extends('layouts.client')

@section('title', 'Mes réservations')

@section('content')
<div class="p-4 lg:p-8 max-w-4xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
      <h1 class="text-2xl lg:text-3xl font-extrabold mb-1" style="color: var(--color-text);">Mes réservations</h1>
      <p class="text-sm" style="color: var(--color-text-secondary);">Suivi : en attente, accepté, en route, terminé</p>
    </div>
    <a href="{{ route('client.reservations.create') }}" class="inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl text-white" style="background: var(--color-primary);">Nouvelle réservation</a>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-800">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-red-50 border border-red-200 text-red-700">{{ session('error') }}</div>
  @endif

  <div class="space-y-4">
    @forelse($reservations as $r)
      <a href="{{ route('client.reservations.show', $r->id) }}" class="block bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-4 p-6">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold" style="background: var(--color-primary);">
              {{ strtoupper(mb_substr($r->prestataire->user->name ?? 'P', 0, 1)); }}
            </div>
            <div>
              <p class="font-bold" style="color: var(--color-text);">{{ $r->prestataire->user->name ?? 'Prestataire' }}</p>
              <p class="text-sm" style="color: var(--color-text-secondary);">
                {{ $r->type_demande === 'programmee' ? 'Programmée' : 'Immédiate' }}
                @if($r->date_prevue) · {{ $r->date_prevue->format('d/m/Y H:i') }} @endif
              </p>
              @if($r->adresse_intervention)<p class="text-xs mt-0.5" style="color: var(--color-text-secondary);">{{ Str::limit($r->adresse_intervention, 50) }}</p>@endif
            </div>
          </div>
          <span class="text-xs font-semibold px-3 py-1.5 rounded-full
            @if($r->status === 'en_attente') bg-amber-100 text-amber-800
            @elseif($r->status === 'accepte' || $r->status === 'en_route') bg-teal-100 text-teal-800
            @elseif($r->status === 'termine') bg-gray-100 text-gray-700
            @elseif($r->status === 'refuse' || $r->status === 'annule') bg-red-50 text-red-700
            @else bg-gray-100 text-gray-600
            @endif">
            {{ \App\Models\Reservation::statusLabel($r->status) }}
          </span>
        </div>
      </a>
    @empty
      <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 p-12 text-center">
        <p class="mb-4" style="color: var(--color-text-secondary);">Aucune réservation.</p>
        <a href="{{ route('client.search') }}" class="inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl text-white" style="background: var(--color-primary);">Réserver un service</a>
      </div>
    @endforelse
  </div>

  @if($reservations->hasPages())
    <div class="mt-8">{{ $reservations->links() }}</div>
  @endif
</div>
@endsection
