@extends('layouts.app')

@section('title', 'Profil prestataire')

@section('content')
<section class="py-20 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-6 lg:px-16">
        <h1 class="text-3xl font-extrabold text-slate-800 mb-6">Profil prestataire #{{ $id }}</h1>
        <p class="text-slate-500 mb-8">
            Fiche du prestataire avec avis, disponibilité et bouton « Réserver ».
        </p>
        <a href="{{ url('/reservations/create?provider='.$id) }}" class="btn-primary btn inline-flex">Réserver ce prestataire</a>
    </div>
</section>
@endsection
