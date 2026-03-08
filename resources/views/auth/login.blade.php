@extends('layouts.app')

@section('title', 'Connexion')

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
        <h1 class="text-2xl font-extrabold" style="color: var(--sm-text);">Connexion</h1>
        <p class="text-sm mt-1" style="color: var(--sm-muted);">Accédez à votre compte ServeMe</p>
      </div>

      @if ($errors->any())
        <div class="auth-errors">
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <form action="{{ url('/login') }}" method="POST" class="auth-form">
        @csrf
        <div class="auth-field">
          <label for="email" class="auth-label">Adresse e-mail</label>
          <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                 class="auth-input" placeholder="vous@exemple.com">
        </div>
        <div class="auth-field">
          <label for="password" class="auth-label">Mot de passe</label>
          <input type="password" name="password" id="password" required autocomplete="current-password"
                 class="auth-input" placeholder="••••••••">
        </div>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}
                 class="rounded border-slate-300 text-teal-600 focus:ring-teal-500 focus:ring-offset-0">
          <label for="remember" class="text-sm" style="color: var(--sm-muted);">Se souvenir de moi</label>
        </div>
        <button type="submit" class="auth-submit">Se connecter</button>
      </form>

      <p class="text-center text-sm mt-6" style="color: var(--sm-muted);">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color: var(--sm-primary);">S'inscrire</a>
      </p>
    </div>
    <p class="text-center mt-6">
      <a href="{{ url('/') }}" class="text-sm font-medium hover:underline" style="color: var(--sm-muted);">Retour à l'accueil</a>
    </p>
  </div>
</section>
@endsection
