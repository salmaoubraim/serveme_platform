@extends('layouts.app')

@section('title', 'Recherche de services')

@section('content')
<section class="py-20 min-h-screen" style="background: var(--serve-bg-light);">
    <div class="container mx-auto px-6 lg:px-16">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-extrabold mb-2" style="color: var(--serve-text);">Recherche de services</h1>
            <p class="mb-8" style="color: var(--serve-text-muted);">
                Selon le cahier des charges : sélection de la catégorie, localisation et type de demande (immédiate ou programmée).
            </p>

            @php
                $category = request('category');
                $q       = request('q');
                $address  = request('address');
                $type     = request('type', 'immediate');
                $lat      = request('lat');
                $lng      = request('lng');
            @endphp

            @if($category || $address || $q)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-8">
                    <h2 class="text-lg font-semibold mb-4" style="color: var(--serve-text);">Critères de recherche</h2>
                    <ul class="space-y-2 text-sm" style="color: var(--serve-text-muted);">
                        @if($q)
                            <li><span class="font-medium" style="color: var(--serve-text);">Recherche :</span> {{ $q }}</li>
                        @endif
                        @if($category)
                            <li><span class="font-medium" style="color: var(--serve-text);">Catégorie :</span> {{ ucfirst($category) }}</li>
                        @endif
                        @if($address)
                            <li><span class="font-medium" style="color: var(--serve-text);">Adresse :</span> {{ $address }}</li>
                        @endif
                        @if($lat && $lng)
                            <li><span class="font-medium" style="color: var(--serve-text);">Coordonnées :</span> {{ $lat }}, {{ $lng }}</li>
                        @endif
                        <li><span class="font-medium" style="color: var(--serve-text);">Type :</span> {{ $type === 'scheduled' ? 'Programmée' : 'Immédiate' }}</li>
                    </ul>
                </div>
                <p class="mb-6" style="color: var(--serve-text-muted);">
                    Les prestataires disponibles pour ces critères s'afficheront ici (carte et liste) une fois le backend connecté à la base de données.
                </p>
            @else
                <div class="rounded-xl p-6 mb-8 border" style="background: var(--serve-bg-light); border-color: var(--serve-accent);">
                    <p style="color: var(--serve-text);">
                        Utilisez la <strong>barre de recherche</strong> en haut de la page ou le menu <strong>Catégories</strong> pour trouver un service.
                    </p>
                </div>
            @endif

            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-white font-semibold px-5 py-2.5 rounded-lg" style="background: var(--serve-primary);">
                ← Retour à l'accueil
            </a>
        </div>
    </div>
</section>
@endsection
