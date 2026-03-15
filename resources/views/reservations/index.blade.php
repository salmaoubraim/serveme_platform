@extends('layouts.app')

@section('title', 'Mes réservations')

@section('content')
<section class="py-8 lg:py-12 min-h-screen" style="background: var(--sm-slate);">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
      <div class="min-w-0">
        <h1 class="text-2xl lg:text-3xl font-extrabold mb-1" style="color: var(--sm-text);">Mes réservations</h1>
        <p class="text-sm" style="color: var(--sm-muted);">Suivi du statut : en attente, accepté, en route, terminé</p>
      </div>
      <a href="{{ route('reservations.create') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold py-2 px-3 sm:px-4 rounded-lg text-white shrink-0" style="background: var(--sm-primary);">Nouvelle réservation</a>
    </div>

    @if(session('success'))
      <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-red-50 border border-red-200 text-red-700">{{ session('error') }}</div>
    @endif

    <div class="space-y-4">
      @forelse($reservations as $r)
        @php $prestataireName = $r->prestataire?->user?->name ?? 'Prestataire'; @endphp
        <a href="{{ route('reservations.show', $r->id) }}" class="block bg-white rounded-2xl border shadow-sm hover:shadow-md transition-shadow overflow-hidden" style="border-color: var(--sm-border);">
          <div class="flex flex-wrap items-center justify-between gap-4 p-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold" style="background: var(--sm-primary);">
                {{ strtoupper(mb_substr($prestataireName, 0, 1)); }}
              </div>
              <div>
                <p class="font-bold" style="color: var(--sm-text);">{{ $prestataireName }}</p>
                <p class="text-sm" style="color: var(--sm-muted);">
                  {{ $r->type_demande === 'programmee' ? 'Programmée' : 'Immédiate' }}
                  @if($r->date_prevue) · {{ $r->date_prevue->format('d/m/Y H:i') }} @endif
                </p>
                @if($r->adresse_intervention)<p class="text-xs mt-0.5" style="color: var(--sm-muted);">{{ Str::limit($r->adresse_intervention, 50) }}</p>@endif
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-xs font-semibold px-3 py-1.5 rounded-full
                @if($r->status === 'en_attente') bg-amber-100 text-amber-800
                @elseif($r->status === 'accepte' || $r->status === 'en_route') bg-teal-100 text-teal-800
                @elseif($r->status === 'termine') bg-slate-100 text-slate-700
                @elseif($r->status === 'refuse' || $r->status === 'annule') bg-red-50 text-red-700
                @else bg-slate-100 text-slate-600
                @endif">
                {{ \App\Models\Reservation::statusLabel($r->status) }}
              </span>
              <span class="text-slate-400">→</span>
            </div>
          </div>
        </a>
      @empty
        <div class="bg-white rounded-2xl border p-8 sm:p-12 text-center" style="border-color: var(--sm-border);">
          <p class="mb-4" style="color: var(--sm-muted);">Aucune réservation.</p>
          <a href="{{ url('/services') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold py-2 px-4 rounded-lg text-white" style="background: var(--sm-primary);">Rechercher un service</a>
        </div>
      @endforelse
    </div>

    @if($reservations->hasPages())
      <div class="mt-8">{{ $reservations->links() }}</div>
    @endif
  </div>
</section>
@endsection
