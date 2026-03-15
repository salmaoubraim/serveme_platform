<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'ServeMe') }}@hasSection('title') — @yield('title')@endif</title>

  {{-- Google Fonts : Nunito --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  {{-- Tailwind CSS CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#FF6B35',
            'primary-dark': '#E55A2B',
            surface: '#FFFFFF',
            success: '#388E3C',
            error: '#D32F2F',
          },
          fontFamily: {
            sans: ['Nunito', 'sans-serif'],
          },
        },
      },
    }
  </script>

  {{-- Variables CSS projet --}}
  <style>
    :root {
      --color-primary: #FF6B35;
      --color-primary-dark: #E55A2B;
      --color-bg: #FAFAFA;
      --color-surface: #FFFFFF;
      --color-text: #212121;
      --color-text-secondary: #757575;
      --color-success: #388E3C;
      --color-error: #D32F2F;
    }
    body {
      font-family: 'Nunito', sans-serif;
      background-color: var(--color-bg);
      color: var(--color-text);
    }
    [x-cloak] { display: none !important; }
  </style>

  {{-- Leaflet CSS --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous" />

  @livewireStyles
  @stack('styles')
</head>
<body class="min-h-screen antialiased bg-[#FAFAFA]">
  {{-- ═══════════════════════════════════════════════════════════
      TOPBAR fixe (h-16) — logo gauche, notifications + avatar droite
      ═══════════════════════════════════════════════════════════ --}}
  <header class="fixed top-0 left-0 right-0 z-40 h-16 bg-[var(--color-surface)] border-b border-gray-200 flex items-center justify-between px-4 lg:pl-4 lg:pr-6">
    {{-- Gauche : logo ServeMe + icône outil orange --}}
    <a href="{{ route('client.home') }}" class="flex items-center gap-2">
      <svg class="w-8 h-8 text-[#FF6B35]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      <span class="font-extrabold text-lg text-[var(--color-text)]">ServeMe</span>
    </a>

    {{-- Droite : cloche notifications + avatar + nom --}}
    <div class="flex items-center gap-3 sm:gap-4">
      {{-- Cloche notifications (badge rouge si non lues) --}}
      <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" @click.outside="open = false" type="button" class="p-2 rounded-full text-[var(--color-text-secondary)] hover:bg-gray-100 relative" aria-label="Notifications">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          @if(($unreadNotificationsCount ?? 0) > 0)
            @php $n = $unreadNotificationsCount ?? 0; @endphp
            <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[var(--color-error)] text-white text-[10px] font-bold flex items-center justify-center">{{ $n > 9 ? '9+' : $n }}</span>
          @endif
        </button>
        {{-- Dropdown notifications (à connecter à l'API si besoin) --}}
        <div x-show="open" x-cloak x-transition
             class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
          <p class="px-4 py-2 text-sm font-semibold text-[var(--color-text)]">Notifications</p>
          <div class="max-h-64 overflow-y-auto">
            <p class="px-4 py-6 text-sm text-[var(--color-text-secondary)] text-center">Aucune notification</p>
          </div>
        </div>
      </div>

      {{-- Avatar + nom (masqué sur très petit écran, visible sm:) --}}
      <div class="hidden sm:flex items-center gap-2">
        <img src="{{ auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=FF6B35&color=fff&font-size=0.4' }}"
             alt="{{ auth()->user()->name }}"
             class="w-9 h-9 rounded-full object-cover border-2 border-gray-200">
        <span class="font-semibold text-sm text-[var(--color-text)] truncate max-w-[120px]">{{ auth()->user()->name }}</span>
      </div>
    </div>
  </header>

  {{-- ═══════════════════════════════════════════════════════════
      SIDEBAR desktop (256px) — visible lg et plus, cachée sur mobile
      ═══════════════════════════════════════════════════════════ --}}
  <aside class="hidden lg:block fixed left-0 top-16 bottom-0 w-64 bg-[var(--color-surface)] border-r border-gray-200 z-30 flex flex-col">
    {{-- Logo en haut de la sidebar --}}
    <div class="p-4 border-b border-gray-100">
      <a href="{{ route('client.home') }}" class="flex items-center gap-2">
        <svg class="w-7 h-7 text-[#FF6B35] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span class="font-extrabold text-sm text-[var(--color-text)]">ServeMe</span>
      </a>
    </div>

    {{-- Liens de navigation avec icônes Heroicons --}}
    <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
      <a href="{{ route('client.home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.home') ? 'bg-orange-50 text-[#FF6B35]' : 'text-[var(--color-text)] hover:bg-gray-50' }}">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Accueil
      </a>
      <a href="{{ route('client.search') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.search') ? 'bg-orange-50 text-[#FF6B35]' : 'text-[var(--color-text)] hover:bg-gray-50' }}">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        Rechercher
      </a>
      <a href="{{ route('client.historique') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.historique') || request()->routeIs('reservations.*') ? 'bg-orange-50 text-[#FF6B35]' : 'text-[var(--color-text)] hover:bg-gray-50' }}">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        Réservations
      </a>
      <a href="{{ route('client.messagerie') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.messagerie') ? 'bg-orange-50 text-[#FF6B35]' : 'text-[var(--color-text)] hover:bg-gray-50' }}">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        Messagerie
      </a>
      <a href="{{ route('client.profil') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.profil') || request()->routeIs('profile.*') ? 'bg-orange-50 text-[#FF6B35]' : 'text-[var(--color-text)] hover:bg-gray-50' }}">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Profil
      </a>
    </nav>

    {{-- Bouton déconnexion en bas (rouge) --}}
    <div class="p-3 border-t border-gray-200">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium text-[var(--color-error)] hover:bg-red-50 transition-colors">
          <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Déconnexion
        </button>
      </form>
    </div>
  </aside>

  {{-- ═══════════════════════════════════════════════════════════
      ZONE PRINCIPALE : contenu avec marge gauche sidebar + marge basse bottom nav
      ═══════════════════════════════════════════════════════════ --}}
  <main class="pt-16 lg:ml-64 pb-20 min-h-screen">
    @yield('content')
  </main>

  {{-- ═══════════════════════════════════════════════════════════
      BOTTOM NAV mobile (4 icônes) — fixe en bas, cachée sur lg
      ═══════════════════════════════════════════════════════════ --}}
  <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 h-16 bg-[var(--color-surface)] border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] flex items-center justify-around px-2">
    <a href="{{ route('client.home') }}" class="flex flex-col items-center justify-center flex-1 py-2 text-[var(--color-text-secondary)] {{ request()->routeIs('client.home') ? 'text-[#FF6B35]' : '' }}">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
      </svg>
      <span class="text-[10px] font-medium mt-0.5">Accueil</span>
    </a>
    <a href="{{ route('client.search') }}" class="flex flex-col items-center justify-center flex-1 py-2 text-[var(--color-text-secondary)] {{ request()->routeIs('client.search') ? 'text-[#FF6B35]' : '' }}">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
      <span class="text-[10px] font-medium mt-0.5">Recherche</span>
    </a>
    <a href="{{ route('client.historique') }}" class="flex flex-col items-center justify-center flex-1 py-2 text-[var(--color-text-secondary)] {{ request()->routeIs('client.historique') || request()->routeIs('reservations.*') ? 'text-[#FF6B35]' : '' }}">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
      </svg>
      <span class="text-[10px] font-medium mt-0.5">Réservations</span>
    </a>
    <a href="{{ route('client.profil') }}" class="flex flex-col items-center justify-center flex-1 py-2 text-[var(--color-text-secondary)] {{ request()->routeIs('client.profil') || request()->routeIs('profile.*') ? 'text-[#FF6B35]' : '' }}">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
      </svg>
      <span class="text-[10px] font-medium mt-0.5">Profil</span>
    </a>
  </nav>

  {{-- ═══════════════════════════════════════════════════════════
      SCRIPTS : Leaflet, Pusher, Echo, Alpine, Livewire
      ═══════════════════════════════════════════════════════════ --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>
    // Laravel Echo (à configurer selon .env PUSHER_*)
    // window.Echo = new Echo({ ... });
  </script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @livewireScripts
  @stack('scripts')
</body>
</html>
