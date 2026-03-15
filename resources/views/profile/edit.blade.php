@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<section class="py-8 lg:py-12 min-h-screen auth-section">
  <div class="w-full max-w-lg mx-auto px-4">
    <h1 class="text-2xl font-extrabold mb-6" style="color: var(--sm-text);">Mon profil</h1>

    @if(session('success'))
      <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-800">
        {{ session('success') }}
      </div>
    @endif

    <div class="auth-card">
      <form action="{{ route('profile.update') }}" method="POST" class="auth-form">
        @csrf
        @method('patch')
        <div class="auth-field">
          <label for="name" class="auth-label">Nom</label>
          <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
                 class="auth-input" placeholder="Votre nom">
          @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="auth-field">
          <label for="email" class="auth-label">Adresse e-mail</label>
          <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                 class="auth-input" placeholder="vous@exemple.com">
          @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="auth-field">
          <label for="phone" class="auth-label">Téléphone (optionnel)</label>
          <input type="text" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}"
                 class="auth-input" placeholder="06 12 34 56 78">
          @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="auth-submit">Enregistrer</button>
      </form>
    </div>

    <p class="text-center mt-6">
      <a href="{{ route('dashboard') }}" class="text-sm font-medium hover:underline" style="color: var(--sm-muted);">Retour au tableau de bord</a>
    </p>
  </div>
</section>
@endsection
