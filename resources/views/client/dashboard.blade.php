@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<section class="py-8 lg:py-12 min-h-screen" style="background: var(--sm-slate);">
  <div class="container mx-auto px-6 lg:px-20">
    <h1 class="text-2xl lg:text-3xl font-extrabold mb-2" style="color: var(--sm-text);">Tableau de bord</h1>
    <p class="text-sm mb-8" style="color: var(--sm-muted);">Résumé de vos réservations et notifications</p>

    @php
      $user = auth()->user();
      $reservations = $user->clientReservations()->get();
      $pending = $reservations->where('status', \App\Models\Reservation::STATUS_EN_ATTENTE)->count();
      $accepted = $reservations->whereIn('status', [\App\Models\Reservation::STATUS_ACCEPTE, \App\Models\Reservation::STATUS_EN_ROUTE])->count();
      $completed = $reservations->where('status', \App\Models\Reservation::STATUS_TERMINE)->count();
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
      <a href="{{ route('reservations.index') }}?status=en_attente" class="bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-shadow" style="border-color: var(--sm-border);">
        <p class="text-3xl font-extrabold mb-1" style="color: var(--sm-primary);">{{ $pending }}</p>
        <p class="text-sm font-semibold" style="color: var(--sm-text);">En attente</p>
      </a>
      <a href="{{ route('reservations.index') }}?status=accepte" class="bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-shadow" style="border-color: var(--sm-border);">
        <p class="text-3xl font-extrabold mb-1" style="color: var(--sm-mid);">{{ $accepted }}</p>
        <p class="text-sm font-semibold" style="color: var(--sm-text);">En cours</p>
      </a>
      <a href="{{ route('reservations.index') }}" class="bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-shadow" style="border-color: var(--sm-border);">
        <p class="text-3xl font-extrabold mb-1" style="color: var(--sm-text);">{{ $reservations->count() }}</p>
        <p class="text-sm font-semibold" style="color: var(--sm-text);">Total</p>
      </a>
      <a href="{{ route('reservations.index') }}?status=termine" class="bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-shadow" style="border-color: var(--sm-border);">
        <p class="text-3xl font-extrabold mb-1" style="color: var(--sm-accent);">{{ $completed }}</p>
        <p class="text-sm font-semibold" style="color: var(--sm-text);">Terminées</p>
      </a>
    </div>

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden" style="border-color: var(--sm-border);">
      <div class="px-4 sm:px-6 py-4 border-b flex flex-wrap items-center justify-between gap-3" style="border-color: var(--sm-border);">
        <h2 class="text-lg font-bold" style="color: var(--sm-text);">Dernières réservations</h2>
        <a href="{{ route('reservations.create') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold py-2 px-3 sm:px-4 rounded-lg text-white shrink-0" style="background: var(--sm-primary);">Nouvelle réservation</a>
      </div>
      <div class="divide-y" style="border-color: var(--sm-border);">
        @forelse($user->clientReservations()->with('prestataire.user')->orderByDesc('created_at')->limit(5)->get() as $r)
          @php $pName = $r->prestataire?->user?->name ?? 'Prestataire'; @endphp
          <a href="{{ route('reservations.show', $r->id) }}" class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background: var(--sm-primary);">
                {{ strtoupper(mb_substr($pName, 0, 1)); }}
              </div>
              <div>
                <p class="font-semibold" style="color: var(--sm-text);">{{ $pName }}</p>
                <p class="text-xs" style="color: var(--sm-muted);">{{ $r->created_at->format('d/m/Y H:i') }} · {{ $r->type_demande === 'programmee' ? 'Programmée' : 'Immédiate' }}</p>
              </div>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full
              @if($r->status === 'en_attente') bg-amber-100 text-amber-800
              @elseif($r->status === 'accepte' || $r->status === 'en_route') bg-teal-100 text-teal-800
              @elseif($r->status === 'termine') bg-slate-100 text-slate-700
              @elseif($r->status === 'refuse' || $r->status === 'annule') bg-red-50 text-red-700
              @else bg-slate-100 text-slate-600
              @endif">
              {{ \App\Models\Reservation::statusLabel($r->status) }}
            </span>
          </a>
        @empty
          <div class="px-4 sm:px-6 py-12 text-center" style="color: var(--sm-muted);">
            <p class="mb-4">Aucune réservation.</p>
            <a href="{{ route('reservations.create') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold py-2 px-4 rounded-lg text-white" style="background: var(--sm-primary);">Réserver un service</a>
          </div>
        @endforelse
      </div>
      @if($user->clientReservations()->count() > 5)
        <div class="px-6 py-3 border-t text-center" style="border-color: var(--sm-border);">
          <a href="{{ route('reservations.index') }}" class="text-sm font-semibold" style="color: var(--sm-primary);">Voir tout</a>
        </div>
      @endif
    </div>

    <div class="mt-10 flex flex-wrap gap-3">
      <a href="{{ url('/services') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-lg font-semibold text-sm text-white" style="background: var(--sm-primary);">Réserver un service</a>
      <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-lg border font-semibold text-sm" style="border-color: var(--sm-border); color: var(--sm-text);">Mon profil</a>
    </div>
  </div>
</section>
@endsection
