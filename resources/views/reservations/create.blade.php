@extends('layouts.app')

@section('title', 'Nouvelle réservation')

@section('content')
<section class="py-20 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-6 lg:px-16">
        <h1 class="text-3xl font-extrabold text-slate-800 mb-6">Nouvelle réservation</h1>
        <p class="text-slate-500 mb-8">
            Formulaire de réservation (type immédiate / programmée, date, description). À connecter au contrôleur et au modèle selon le document de conception.
        </p>
        <a href="{{ url('/services') }}" class="btn-outline btn inline-flex">← Choisir un service</a>
    </div>
</section>
@endsection
