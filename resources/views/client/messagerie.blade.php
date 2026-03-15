@extends('layouts.client')

@section('title', 'Messagerie')

@section('content')
<div class="p-4 lg:p-8 max-w-2xl mx-auto">
  <h1 class="text-2xl lg:text-3xl font-extrabold mb-2" style="color: var(--color-text);">Messagerie</h1>
  <p class="text-sm mb-8" style="color: var(--color-text-secondary);">Échangez avec vos prestataires.</p>

  <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm p-12 text-center">
    <p class="mb-4" style="color: var(--color-text-secondary);">Aucune conversation pour le moment.</p>
    <p class="text-sm" style="color: var(--color-text);">Vos échanges avec les prestataires apparaîtront ici après une réservation.</p>
  </div>
</div>
@endsection
