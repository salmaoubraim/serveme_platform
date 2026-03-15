@extends('layouts.app')

@section('title', 'Contact')

@push('styles')
<style>
  .contact-page-section { display: flex; justify-content: center; align-items: center; width: 100%; }
  .contact-page-section .contact-inner { max-width: 56rem; width: 100%; margin-left: auto; margin-right: auto; }
</style>
@endpush

@section('content')
<section class="contact-page-section min-h-screen py-8 lg:py-12" style="background: var(--sm-slate);">
  <div class="contact-inner max-w-4xl w-full px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-6 lg:mb-8">
      <h1 class="text-2xl sm:text-3xl font-extrabold mb-2" style="color: var(--sm-text);">Contact</h1>
      <p class="text-sm sm:text-base" style="color: var(--sm-muted);">Une question, un partenariat ou besoin d’aide ? Écrivez-nous ou venez nous voir à Agadir.</p>
    </div>

    @if(session('success'))
      <div class="mb-6 p-4 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-800 text-center max-w-xl mx-auto">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2 w-full">
      {{-- Coordonnées — Agadir —}}
      <div class="bg-white rounded-2xl border shadow-sm p-5 sm:p-6 lg:p-8" style="border-color: var(--sm-border);">
        <h2 class="text-lg font-bold mb-5" style="color: var(--sm-text);">Nous trouver</h2>
        <ul class="space-y-4">
          <li class="flex items-start gap-3 sm:gap-4">
            <span class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--sm-light); color: var(--sm-primary);">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
            <div class="min-w-0">
              <p class="font-semibold text-sm" style="color: var(--sm-text);">Adresse</p>
              <p class="text-sm" style="color: var(--sm-muted);">Agadir, Maroc</p>
            </div>
          </li>
          <li class="flex items-start gap-3 sm:gap-4">
            <span class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--sm-light); color: var(--sm-primary);">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
            <div class="min-w-0">
              <p class="font-semibold text-sm" style="color: var(--sm-text);">Email</p>
              <a href="mailto:contact@serveme.ma" class="text-sm hover:underline break-all" style="color: var(--sm-primary);">contact@serveme.ma</a>
            </div>
          </li>
          <li class="flex items-start gap-3 sm:gap-4">
            <span class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--sm-light); color: var(--sm-primary);">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </span>
            <div class="min-w-0">
              <p class="font-semibold text-sm" style="color: var(--sm-text);">Téléphone</p>
              <a href="tel:+212528000000" class="text-sm hover:underline" style="color: var(--sm-primary);">+212 528 000 000</a>
            </div>
          </li>
        </ul>
      </div>

      {{-- Formulaire centré et responsive --}}
      <div class="bg-white rounded-2xl border shadow-sm p-5 sm:p-6 lg:p-8" style="border-color: var(--sm-border);">
        <h2 class="text-lg font-bold mb-4" style="color: var(--sm-text);">Nous écrire</h2>
        <p class="text-sm mb-5" style="color: var(--sm-muted);">Remplissez le formulaire et nous vous répondrons sous 48 h.</p>
        <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
          @csrf
          <div>
            <label for="contact_name" class="auth-label block mb-1">Nom</label>
            <input type="text" name="name" id="contact_name" required value="{{ old('name') }}" class="auth-input w-full" placeholder="Votre nom">
            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="contact_email" class="auth-label block mb-1">Email</label>
            <input type="email" name="email" id="contact_email" required value="{{ old('email') }}" class="auth-input w-full" placeholder="vous@exemple.com">
            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="contact_message" class="auth-label block mb-1">Message</label>
            <textarea name="message" id="contact_message" rows="4" required class="auth-input w-full resize-y min-h-[100px]" placeholder="Votre message...">{{ old('message') }}</textarea>
            @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
          </div>
          <button type="submit" class="auth-submit">Envoyer</button>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
