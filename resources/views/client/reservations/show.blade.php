@extends('layouts.client')

@section('title', 'Réservation #' . $reservation->id)

@section('content')
<div class="p-4 lg:p-8 max-w-2xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-extrabold" style="color: var(--color-text);">Réservation #{{ $reservation->id }}</h1>
    <a href="{{ route('client.historique') }}" class="text-sm font-medium hover:underline" style="color: var(--color-text-secondary);">← Mes réservations</a>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-800">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-red-50 border border-red-200 text-red-700">{{ session('error') }}</div>
  @endif

  <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
    <h2 class="text-sm font-bold uppercase tracking-wider mb-4" style="color: var(--color-text-secondary);">Suivi du statut</h2>
    @php
      $steps = [
        'en_attente' => ['label' => 'Demande envoyée',      'done' => in_array($reservation->status, ['en_attente','accepte','en_route','termine'])],
        'accepte'    => ['label' => 'Prestataire accepté',  'done' => in_array($reservation->status, ['accepte','en_route','termine'])],
        'en_route'   => ['label' => 'Prestataire en route', 'done' => in_array($reservation->status, ['en_route','termine'])],
        'termine'    => ['label' => 'Terminé',             'done' => $reservation->status === 'termine'],
      ];
      $refused = $reservation->status === 'refuse';
      $cancelled = $reservation->status === 'annule';
    @endphp
    @if($refused)
      <p class="font-semibold" style="color: var(--color-error);">Demande refusée</p>
    @elseif($cancelled)
      <p class="font-semibold text-gray-600">Réservation annulée</p>
    @else
      <div class="space-y-3">
        @foreach($steps as $step)
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $step['done'] ? 'text-white' : 'bg-gray-100' }}" style="{{ $step['done'] ? 'background: var(--color-primary);' : '' }}">
              @if($step['done'])<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
              @else<span class="text-xs font-bold text-gray-400">—</span>@endif
            </div>
            <span class="{{ $step['done'] ? 'font-semibold' : '' }}" style="color: var(--color-text);">{{ $step['label'] }}</span>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
    <h2 class="text-sm font-bold uppercase tracking-wider mb-4" style="color: var(--color-text-secondary);">Détails</h2>
    <dl class="space-y-3 text-sm">
      <div class="flex justify-between"><dt style="color: var(--color-text-secondary);">Prestataire</dt><dd class="font-semibold" style="color: var(--color-text);">{{ $reservation->prestataire->user->name ?? '—' }}</dd></div>
      @if($reservation->service)
        <div class="flex justify-between"><dt style="color: var(--color-text-secondary);">Service</dt><dd style="color: var(--color-text);">{{ $reservation->service->name }}</dd></div>
      @endif
      <div class="flex justify-between"><dt style="color: var(--color-text-secondary);">Type</dt><dd style="color: var(--color-text);">{{ $reservation->type_demande === 'programmee' ? 'Programmée' : 'Immédiate' }}</dd></div>
      @if($reservation->date_prevue)
        <div class="flex justify-between"><dt style="color: var(--color-text-secondary);">Date prévue</dt><dd style="color: var(--color-text);">{{ $reservation->date_prevue->format('d/m/Y H:i') }}</dd></div>
      @endif
      @if($reservation->adresse_intervention)
        <div class="flex justify-between"><dt style="color: var(--color-text-secondary);">Adresse</dt><dd style="color: var(--color-text);">{{ $reservation->adresse_intervention }}</dd></div>
      @endif
    </dl>
  </div>

  <div class="flex flex-wrap gap-3">
    @if($reservation->canBeCancelled())
      <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette réservation ?');">
        @csrf
        @method('patch')
        <button type="submit" class="px-4 py-2 rounded-xl border font-semibold text-sm border-red-200 hover:bg-red-50" style="color: var(--color-error);">Annuler la réservation</button>
      </form>
    @endif
    @if($reservation->canBeReviewed())
      <a href="#review" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white font-semibold text-sm" style="background: var(--color-primary);">Évaluer le service</a>
    @endif
  </div>

  @if($reservation->canBeReviewed() && !$reservation->avis)
    <div id="review" class="mt-10 bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm p-6">
      <h2 class="text-lg font-bold mb-4" style="color: var(--color-text);">Évaluation</h2>
      <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
        <div>
          <label class="block text-sm font-medium mb-2" style="color: var(--color-text);">Note (1 à 5)</label>
          <div class="flex gap-2">
            @for($i = 1; $i <= 5; $i++)
              <label class="cursor-pointer">
                <input type="radio" name="note" value="{{ $i }}" {{ old('note') == $i ? 'checked' : '' }} class="sr-only peer">
                <span class="inline-flex w-10 h-10 rounded-full border-2 border-gray-300 items-center justify-center font-bold text-sm peer-checked:border-[var(--color-primary)] peer-checked:bg-orange-50">{{ $i }}</span>
              </label>
            @endfor
          </div>
        </div>
        <div>
          <label for="review_comment" class="block text-sm font-medium mb-2" style="color: var(--color-text);">Commentaire (optionnel)</label>
          <textarea name="commentaire" id="review_comment" rows="3" placeholder="Votre avis..." class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[var(--color-primary)]">{{ old('commentaire') }}</textarea>
        </div>
        <button type="submit" class="py-2.5 px-5 rounded-xl text-white font-bold text-sm" style="background: var(--color-primary);">Envoyer l'évaluation</button>
      </form>
    </div>
  @endif
</div>
@endsection
