@extends('layouts.app')

@section('title', 'À propos')

@section('content')
<section class="py-8 lg:py-12 min-h-screen" style="background: var(--sm-slate);">
  <div class="container mx-auto px-6 lg:px-20 max-w-3xl">
    <h1 class="text-2xl lg:text-3xl font-extrabold mb-2" style="color: var(--sm-text);">À propos de ServeMe</h1>
    <p class="text-sm mb-8" style="color: var(--sm-muted);">Votre plateforme de mise en relation avec des prestataires de confiance à Agadir et environs.</p>

    <div class="bg-white rounded-2xl border shadow-sm p-6 lg:p-8 space-y-6" style="border-color: var(--sm-border);">
      <p style="color: var(--sm-text);">
        <strong>ServeMe</strong> permet de réserver rapidement des prestataires pour la plomberie, l’électricité, le jardinage, les cours particuliers, la mécanique auto, le nettoyage et bien d’autres services à domicile ou pour les entreprises.
      </p>
      <p style="color: var(--sm-text);">
        Choisissez une catégorie, consultez les profils et les avis, puis envoyez votre demande. Les prestataires validés vous répondent et interviennent selon le type de demande (immédiate ou programmée).
      </p>
      <p style="color: var(--sm-muted);" class="text-sm">
        Nous sommes basés à <strong style="color: var(--sm-text);">Agadir, Maroc</strong>. Pour toute question, rendez-vous sur la page <a href="{{ route('contact') }}" class="font-semibold" style="color: var(--sm-primary);">Contact</a>.
      </p>
    </div>
  </div>
</section>
@endsection
