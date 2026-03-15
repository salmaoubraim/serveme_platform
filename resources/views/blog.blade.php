@extends('layouts.app')

@section('title', 'Actualités')

@section('content')
<section class="py-8 lg:py-12 min-h-screen" style="background: var(--sm-slate);">
  <div class="container mx-auto px-6 lg:px-20">
    <h1 class="text-2xl lg:text-3xl font-extrabold mb-2" style="color: var(--sm-text);">Actualités</h1>
    <p class="text-sm mb-8" style="color: var(--sm-muted);">Conseils, nouveautés et actualités ServeMe.</p>

    <div class="max-w-2xl">
      <div class="bg-white rounded-2xl border shadow-sm p-6 lg:p-8 text-center" style="border-color: var(--sm-border);">
        <p class="text-lg font-semibold mb-2" style="color: var(--sm-text);">Bientôt des articles ici</p>
        <p class="text-sm mb-6" style="color: var(--sm-muted);">Nous préparons des contenus utiles sur les services à domicile, les bonnes pratiques et les offres. Revenez plus tard ou explorez nos services en attendant.</p>
        <a href="{{ url('/services') }}" class="inline-flex items-center gap-2 text-sm font-semibold py-2 px-4 rounded-lg text-white" style="background: var(--sm-primary);">Voir les services</a>
      </div>
    </div>
  </div>
</section>
@endsection
