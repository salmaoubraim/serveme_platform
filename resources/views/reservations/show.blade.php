@extends('layouts.app')

@section('title', 'Réservation #' . $reservation->id)

@section('content')
<section class="py-8 lg:py-12 min-h-screen" style="background: var(--sm-slate);">
  <div class="container mx-auto px-6 lg:px-20 max-w-2xl">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl font-extrabold" style="color: var(--sm-text);">Réservation #{{ $reservation->id }}</h1>
      <a href="{{ route('reservations.index') }}" class="text-sm font-medium hover:underline" style="color: var(--sm-muted);">← Mes réservations</a>
    </div>

    @if(session('success'))
      <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-red-50 border border-red-200 text-red-700">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl border shadow-sm p-6 mb-6" style="border-color: var(--sm-border);">
      <h2 class="text-sm font-bold uppercase tracking-wider mb-4" style="color: var(--sm-muted);">Suivi du statut</h2>
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
        <p class="text-red-600 font-semibold">Demande refusée</p>
      @elseif($cancelled)
        <p class="text-slate-600 font-semibold">Réservation annulée</p>
      @else
        <div class="space-y-3">
          @foreach($steps as $key => $step)
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                {{ $step['done'] ? 'bg-teal-600 text-white' : 'bg-slate-100' }}">
                @if($step['done'])<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                @else<span class="text-xs font-bold" style="color: var(--sm-muted);">—</span>@endif
              </div>
              <span class="{{ $step['done'] ? 'font-semibold' : '' }}" style="color: var(--sm-text);">{{ $step['label'] }}</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <div class="bg-white rounded-2xl border shadow-sm p-6 mb-6" style="border-color: var(--sm-border);">
      <h2 class="text-sm font-bold uppercase tracking-wider mb-4" style="color: var(--sm-muted);">Détails</h2>
      <dl class="space-y-3 text-sm">
        <div class="flex justify-between"><dt style="color: var(--sm-muted);">Prestataire</dt><dd class="font-semibold" style="color: var(--sm-text);">{{ $reservation->prestataire?->user?->name ?? '—' }}</dd></div>
        @if($reservation->service)
          <div class="flex justify-between"><dt style="color: var(--sm-muted);">Service</dt><dd style="color: var(--sm-text);">{{ $reservation->service->name }}</dd></div>
        @endif
        <div class="flex justify-between"><dt style="color: var(--sm-muted);">Type</dt><dd style="color: var(--sm-text);">{{ $reservation->type_demande === 'programmee' ? 'Programmée' : 'Immédiate' }}</dd></div>
        @if($reservation->date_prevue)
          <div class="flex justify-between"><dt style="color: var(--sm-muted);">Date prévue</dt><dd style="color: var(--sm-text);">{{ $reservation->date_prevue->format('d/m/Y H:i') }}</dd></div>
        @endif
        @if($reservation->adresse_intervention)
          <div class="flex justify-between"><dt style="color: var(--sm-muted);">Adresse</dt><dd style="color: var(--sm-text);">{{ $reservation->adresse_intervention }}</dd></div>
        @endif
      </dl>
    </div>

    <div class="flex flex-wrap gap-2 sm:gap-3">
      @if($reservation->canBeCancelled())
        <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette réservation ?');">
          @csrf
          @method('patch')
          <button type="submit" class="py-2 px-3 rounded-lg border font-semibold text-sm text-red-600 border-red-200 hover:bg-red-50">Annuler la réservation</button>
        </form>
      @endif
      @if($reservation->canBeReviewed())
        <a href="#review" class="inline-flex items-center gap-1.5 py-2 px-3 rounded-lg text-white font-semibold text-sm" style="background: var(--sm-primary);">Évaluer le service</a>
      @endif
    </div>

    @if($reservation->canBeReviewed() && !$reservation->avis)
      <div id="review" class="mt-10 bg-white rounded-2xl border shadow-sm p-6" style="border-color: var(--sm-border);">
        <h2 class="text-lg font-bold mb-4" style="color: var(--sm-text);">Évaluation</h2>
        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
          @csrf
          <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
          <div class="auth-field">
            <label class="auth-label">Note (1 à 5)</label>
            <div class="flex gap-2">
              @for($i = 1; $i <= 5; $i++)
                <label class="cursor-pointer">
                  <input type="radio" name="note" value="{{ $i }}" {{ old('note') == $i ? 'checked' : '' }} class="sr-only peer">
                  <span class="inline-flex w-10 h-10 rounded-full border-2 items-center justify-center font-bold text-sm peer-checked:border-teal-600 peer-checked:bg-teal-50" style="border-color: var(--sm-border);">{{ $i }}</span>
                </label>
              @endfor
            </div>
          </div>
          <div class="auth-field">
            <label for="review_comment" class="auth-label">Commentaire (optionnel)</label>
            <textarea name="commentaire" id="review_comment" rows="3" class="auth-input" placeholder="Votre avis...">{{ old('commentaire') }}</textarea>
          </div>
          <button type="submit" class="auth-submit">Envoyer l'évaluation</button>
        </form>
      </div>
    @endif
  </div>
</section>
@endsection
