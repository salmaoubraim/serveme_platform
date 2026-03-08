@extends('layouts.app')

@section('title', 'Mes réservations')

@section('content')
<section class="py-20 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-6 lg:px-16">
        <h1 class="text-3xl font-extrabold text-slate-800 mb-6">Mes réservations</h1>
        <p class="text-slate-500 mb-8">
            Liste des réservations avec suivi du statut (en attente, accepté, en route, terminé).
        </p>
        <a href="{{ url('/services') }}" class="btn-primary btn inline-flex">Nouvelle réservation</a>
    </div>
</section>
@endsection
