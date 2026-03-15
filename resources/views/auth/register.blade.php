@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center py-12 px-4 auth-section">
  <div class="w-full max-w-md">
    <div class="auth-card">
      <div class="text-center mb-8">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-6">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--sm-primary);">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <span class="text-xl font-extrabold" style="color: var(--sm-text);">Serve<span style="color: var(--sm-primary);">Me</span></span>
        </a>
        <h1 class="text-2xl font-extrabold" style="color: var(--sm-text);">Inscription</h1>
        <p class="text-sm mt-1" style="color: var(--sm-muted);">Créez votre compte</p>
      </div>

      {{-- Choix du type de compte : Client ou Prestataire --}}
      <div class="auth-field mb-4">
        <span class="auth-label block mb-3">Je m'inscris en tant que</span>
        <div class="grid grid-cols-2 gap-3">
          <label class="auth-role-option {{ old('role', request('role', 'client')) === 'client' ? 'auth-role-option-active' : '' }}">
            <input type="radio" name="role" value="client" {{ old('role', request('role', 'client')) === 'client' ? 'checked' : '' }} class="sr-only peer">
            <span class="auth-role-icon">👤</span>
            <span class="auth-role-label">Client</span>
            <span class="auth-role-desc">Rechercher et réserver des services</span>
          </label>
          <label class="auth-role-option {{ old('role', request('role', 'client')) === 'prestataire' ? 'auth-role-option-active' : '' }}">
            <input type="radio" name="role" value="prestataire" {{ old('role', request('role', 'client')) === 'prestataire' ? 'checked' : '' }} class="sr-only peer">
            <span class="auth-role-icon">🔧</span>
            <span class="auth-role-label">Prestataire</span>
            <span class="auth-role-desc">Proposer mes services</span>
          </label>
        </div>
      </div>

      @if ($errors->any())
        <div class="auth-errors">
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <form action="{{ url('/register') }}" method="POST" class="auth-form">
        @csrf
        <div class="auth-field">
          <label for="name" class="auth-label">Nom</label>
          <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                 class="auth-input" placeholder="Votre nom">
        </div>
        <div class="auth-field">
          <label for="email" class="auth-label">Adresse e-mail</label>
          <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                 class="auth-input" placeholder="vous@exemple.com">
        </div>
        <div class="auth-field">
          <label for="password" class="auth-label">Mot de passe</label>
          <input type="password" name="password" id="password" required autocomplete="new-password"
                 class="auth-input" placeholder="••••••••">
          <p class="text-xs mt-1" style="color: var(--sm-muted);">Minimum 8 caractères</p>
        </div>
        <div class="auth-field">
          <label for="password_confirmation" class="auth-label">Confirmer le mot de passe</label>
          <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                 class="auth-input" placeholder="••••••••">
        </div>
        <button type="submit" class="auth-submit">S'inscrire</button>
      </form>

      <p class="text-center text-sm mt-6" style="color: var(--sm-muted);">
        Déjà un compte ?
        <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color: var(--sm-primary);">Se connecter</a>
      </p>
    </div>
    <p class="text-center mt-6">
      <a href="{{ url('/') }}" class="text-sm font-medium hover:underline" style="color: var(--sm-muted);">Retour à l'accueil</a>
    </p>
  </div>
</section>
@endsection
