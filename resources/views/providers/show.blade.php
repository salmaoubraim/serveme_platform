@extends('layouts.app')

@section('title', $prestataire->user->name . ' — Prestataire')

@section('content')
<section class="py-8 lg:py-12 min-h-screen" style="background: var(--sm-slate);">
  <div class="container mx-auto px-6 lg:px-20 max-w-3xl">
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden" style="border-color: var(--sm-border);">
      <div class="p-6 lg:p-8">
        <div class="flex flex-wrap items-start gap-6">
          <div class="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold text-white flex-shrink-0" style="background: var(--sm-primary);">
            {{ strtoupper(mb_substr($prestataire->user->name ?? 'P', 0, 1)); }}
          </div>
          <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-extrabold mb-1" style="color: var(--sm-text);">{{ $prestataire->user->name ?? 'Prestataire' }}</h1>
            <p class="text-sm mb-4" style="color: var(--sm-muted);">Prestataire ServeMe</p>
            @php $servicesDisplay = isset($categoryId) && $categoryId ? $prestataire->services->where('category_id', $categoryId) : $prestataire->services; @endphp
            @if($servicesDisplay->isNotEmpty())
              <a href="{{ route('reservations.create') }}?provider={{ $prestataire->id }}{{ isset($categoryId) && $categoryId ? '&category='.$categoryId : '' }}" class="inline-flex items-center gap-1.5 text-sm font-semibold py-2 px-3 sm:px-4 rounded-lg text-white" style="background: var(--sm-primary);">
                Réserver ce prestataire
              </a>
            @else
              <p class="text-sm text-amber-600">Aucun service proposé pour le moment.</p>
            @endif
          </div>
        </div>
        @if($servicesDisplay->isNotEmpty())
          <div class="mt-6 pt-6 border-t" style="border-color: var(--sm-border);">
            <h2 class="text-sm font-bold uppercase tracking-wider mb-3" style="color: var(--sm-muted);">Services proposés{{ isset($categoryId) && $categoryId ? ' (cette catégorie)' : '' }}</h2>
            <ul class="space-y-2">
              @foreach($servicesDisplay as $svc)
                <li class="flex justify-between items-center text-sm">
                  <span style="color: var(--sm-text);">{{ $svc->name }}</span>
                  @if($svc->price !== null)<span style="color: var(--sm-muted);">{{ number_format($svc->price, 2, ',', ' ') }} {{ config('app.currency_symbol', 'MAD') }}</span>@endif
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </div>

    <p class="mt-6">
      <a href="{{ url('/services') }}{{ isset($categoryId) && $categoryId ? '?category='.$categoryId : '' }}" class="text-sm font-medium hover:underline" style="color: var(--sm-muted);">← Retour à la recherche</a>
    </p>
  </div>
</section>
@endsection
