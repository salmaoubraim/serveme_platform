@extends('layouts.client')

@section('title', 'Mon profil')

@section('content')
<div class="p-4 lg:p-8 max-w-lg mx-auto">
  <h1 class="text-2xl font-extrabold mb-6" style="color: var(--color-text);">Mon profil</h1>

  @if(session('success'))
    <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-800">
      {{ session('success') }}
    </div>
  @endif

  <div class="bg-[var(--color-surface)] rounded-2xl border border-gray-200 shadow-sm p-6">
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
      @csrf
      @method('patch')
      <div>
        <label for="name" class="block text-sm font-medium mb-2" style="color: var(--color-text);">Nom</label>
        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[var(--color-primary)]"
               placeholder="Votre nom">
        @error('name')<p class="text-xs mt-1" style="color: var(--color-error);">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="email" class="block text-sm font-medium mb-2" style="color: var(--color-text);">Adresse e-mail</label>
        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[var(--color-primary)]"
               placeholder="vous@exemple.com">
        @error('email')<p class="text-xs mt-1" style="color: var(--color-error);">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="phone" class="block text-sm font-medium mb-2" style="color: var(--color-text);">Téléphone (optionnel)</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}"
               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-[var(--color-primary)]"
               placeholder="06 12 34 56 78">
        @error('phone')<p class="text-xs mt-1" style="color: var(--color-error);">{{ $message }}</p>@enderror
      </div>
      <button type="submit" class="w-full py-3 rounded-xl text-white font-bold text-sm" style="background: var(--color-primary);">Enregistrer</button>
    </form>
  </div>

  <p class="text-center mt-6">
    <a href="{{ route('client.home') }}" class="text-sm font-medium hover:underline" style="color: var(--color-text-secondary);">← Retour à l'accueil</a>
  </p>
</div>
@endsection
