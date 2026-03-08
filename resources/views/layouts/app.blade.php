<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
      class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="ServeMe — Trouvez des prestataires de confiance près de vous instantanément.">
  <title>{{ config('app.name', 'ServeMe') }}@hasSection('title') — @yield('title')@endif</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
  @stack('styles')

  @auth
  @php
    $_u = auth()->user();
    $_avatar = $_u->avatar ?? $_u->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($_u->name).'&background=1F6E6C&color=fff&bold=true';
    $_authData = ['id'=>$_u->id,'name'=>$_u->name,'email'=>$_u->email,'avatar'=>$_avatar];
  @endphp
  <script>window.__AUTH_USER__=@json($_authData);window.__AUTH_ROLE__=@json($_u->role ?? 'client');</script>
  @endauth

  <style>
  /* ── Design tokens ────────────────────────────────────── */
  :root {
    --sm-dark:    #1a3c3a;
    --sm-primary: #1F6E6C;
    --sm-mid:     #2a8a87;
    --sm-accent:  #6EC4B9;
    --sm-light:   #e8f5f4;
    --sm-white:   #ffffff;
    --sm-slate:   #f4f7f6;
    --sm-text:    #1e2d2c;
    --sm-muted:   #5a7370;
    --sm-border:  #d4e8e6;
  }
  * { box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', 'Noto Sans Arabic', sans-serif; color: var(--sm-text); }

  /* ── Main navbar ──────────────────────────────────────── */
  .sm-navbar {
    background: var(--sm-white);
    border-bottom: 1px solid var(--sm-border);
    position: sticky;
    top: 0;
    z-index: 100;
    transition: box-shadow .25s;
  }
  .sm-navbar.scrolled {
    box-shadow: 0 2px 16px -4px rgba(31,110,108,.15);
  }

  /* ── Nav links ────────────────────────────────────────── */
  .nav-link {
    color: var(--sm-text);
    font-size: .875rem;
    font-weight: 600;
    padding: .35rem 0;
    position: relative;
    text-decoration: none;
    transition: color .15s;
  }
  .nav-link::after {
    content: '';
    position: absolute;
    bottom: -2px; left: 0; right: 0;
    height: 2px;
    background: var(--sm-primary);
    border-radius: 2px;
    transform: scaleX(0);
    transition: transform .2s;
  }
  .nav-link:hover, .nav-link.active { color: var(--sm-primary); }
  .nav-link:hover::after, .nav-link.active::after { transform: scaleX(1); }

  /* ── Barre de recherche organisée (pill, fond blanc, icône à droite) ────────────── */
  .search-bar-wrap {
    display: flex;
    align-items: center;
    flex-shrink: 0;
    min-width: 0;
  }
  .search-bar-wrap .search-bar-label {
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--sm-muted);
    margin-bottom: 0.25rem;
  }
  .google-like-search {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    height: 40px;
    min-width: 200px;
    max-width: 300px;
    width: 100%;
    padding: 0 1rem 0 1.25rem;
    background: #fff;
    border: 1px solid #1e2d2c;
    border-radius: 9999px;
    transition: box-shadow .2s, border-color .2s;
  }
  .google-like-search:focus-within {
    border-color: var(--sm-primary);
    box-shadow: 0 0 0 2px rgba(31, 110, 108, 0.2);
  }
  .google-like-search input {
    flex: 1;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: none;
    background: transparent;
    font-size: 0.9375rem;
    color: #1e2d2c;
    outline: none;
  }
  .google-like-search input::placeholder {
    color: #5a7370;
  }
  .google-like-search .search-icon {
    flex-shrink: 0;
    color: #1e2d2c;
  }
  .google-like-search button[type="submit"] {
    flex-shrink: 0;
    padding: 0.25rem;
    border: none;
    background: transparent;
    color: var(--sm-primary);
    cursor: pointer;
    border-radius: 50%;
    line-height: 0;
  }
  .google-like-search button[type="submit"]:hover {
    background: rgba(31, 110, 108, 0.1);
  }

  /* ── Formulaires auth (login / register) ───────────────── */
  .auth-section { background: var(--sm-slate); }
  .auth-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 50px -12px rgba(31, 110, 108, 0.15);
    border: 1px solid var(--sm-border);
    padding: 2rem;
    transition: box-shadow .2s;
  }
  .auth-card:hover { box-shadow: 0 24px 56px -14px rgba(31, 110, 108, 0.18); }
  .auth-errors {
    margin-bottom: 1.5rem;
    padding: 1rem 1.25rem;
    border-radius: 14px;
    font-size: 0.875rem;
    font-weight: 500;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
  }
  .auth-form { display: flex; flex-direction: column; gap: 1.25rem; }
  .auth-field { display: flex; flex-direction: column; gap: 0.375rem; }
  .auth-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--sm-text);
  }
  .auth-input {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 0.9375rem;
    color: var(--sm-text);
    background: var(--sm-slate);
    border: 1px solid var(--sm-border);
    border-radius: 12px;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
  }
  .auth-input::placeholder { color: var(--sm-muted); }
  .auth-input:focus {
    border-color: var(--sm-primary);
    box-shadow: 0 0 0 3px rgba(31, 110, 108, 0.15);
  }
  .auth-submit {
    width: 100%;
    padding: 0.875rem 1rem;
    font-size: 0.9375rem;
    font-weight: 700;
    color: #fff;
    background: var(--sm-primary);
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: background .2s, transform .02s;
  }
  .auth-submit:hover { background: var(--sm-mid); }
  .auth-submit:active { transform: scale(0.99); }

  /* ── Choix rôle inscription (Client / Prestataire) ───────── */
  .auth-role-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.35rem;
    padding: 1rem 0.75rem;
    border: 2px solid var(--sm-border);
    border-radius: 14px;
    background: var(--sm-slate);
    cursor: pointer;
    transition: border-color .2s, background .2s, box-shadow .2s;
    text-align: center;
  }
  .auth-role-option:hover { border-color: var(--sm-accent); background: #fff; }
  .auth-role-option-active,
  .auth-role-option:has(input:checked) {
    border-color: var(--sm-primary);
    background: rgba(31, 110, 108, 0.08);
    box-shadow: 0 0 0 1px var(--sm-primary);
  }
  .auth-role-icon { font-size: 1.5rem; line-height: 1; }
  .auth-role-label {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--sm-text);
  }
  .auth-role-desc {
    font-size: 0.75rem;
    color: var(--sm-muted);
    line-height: 1.25;
  }

  .sm-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 50%;
    transform: translateX(-50%);
    background: var(--sm-white);
    border: 1.5px solid var(--sm-border);
    border-radius: 16px;
    box-shadow: 0 16px 40px -8px rgba(31,110,108,.18);
    padding: .5rem;
    min-width: 220px;
    z-index: 200;
  }
  .sm-dropdown-item {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .6rem .85rem;
    border-radius: 10px;
    font-size: .875rem;
    font-weight: 600;
    color: var(--sm-text);
    text-decoration: none;
    transition: background .15s, color .15s;
  }
  .sm-dropdown-item:hover {
    background: var(--sm-light);
    color: var(--sm-primary);
  }

  /* ── Auth buttons ─────────────────────────────────────── */
  .btn-login {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .875rem; font-weight: 700; padding: .55rem 1.1rem;
    border-radius: 10px; border: 2px solid var(--sm-border);
    color: var(--sm-text); background: transparent;
    text-decoration: none; transition: border-color .15s, color .15s;
    white-space: nowrap;
  }
  .btn-login:hover { border-color: var(--sm-primary); color: var(--sm-primary); }

  .btn-register {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .875rem; font-weight: 700; padding: .55rem 1.2rem;
    border-radius: 10px; background: var(--sm-primary);
    color: #fff; text-decoration: none;
    transition: background .15s, transform .1s; white-space: nowrap;
  }
  .btn-register:hover { background: var(--sm-dark); transform: translateY(-1px); }

  /* ── Icon buttons ─────────────────────────────────────── */
  .icon-btn {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    background: var(--sm-slate); border: none; cursor: pointer;
    color: var(--sm-text); transition: background .15s;
    position: relative;
  }
  .icon-btn:hover { background: var(--sm-light); color: var(--sm-primary); }

  /* ── Notification badge ───────────────────────────────── */
  .notif-badge {
    position: absolute;
    top: -4px; right: -4px;
    background: #ef4444;
    color: #fff; font-size: .6rem; font-weight: 800;
    min-width: 18px; height: 18px;
    border-radius: 9px; padding: 0 4px;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
  }

  /* ── User avatar menu ─────────────────────────────────── */
  .user-menu-trigger {
    display: flex; align-items: center; gap: .5rem;
    cursor: pointer; padding: .3rem .5rem;
    border-radius: 10px; transition: background .15s;
    border: none; background: transparent;
  }
  .user-menu-trigger:hover { background: var(--sm-slate); }
  .user-avatar { width: 34px; height: 34px; border-radius: 50%; border: 2px solid var(--sm-accent); object-fit: cover; }

  /* ── Mobile menu ──────────────────────────────────────── */
  .mobile-nav {
    background: var(--sm-white);
    border-top: 1px solid var(--sm-border);
    padding: .75rem 1rem 1.25rem;
  }
  .mobile-nav-link {
    display: flex; align-items: center; gap: .75rem;
    padding: .7rem .75rem; border-radius: 10px;
    font-size: .9rem; font-weight: 600; color: var(--sm-text);
    text-decoration: none; transition: background .15s;
  }
  .mobile-nav-link:hover, .mobile-nav-link.active {
    background: var(--sm-light); color: var(--sm-primary);
  }
  .mobile-divider { height: 1px; background: var(--sm-border); margin: .5rem 0; }

  /* ── Toast notifications ──────────────────────────────── */
  .toast-wrap {
    position: fixed; top: 5rem; right: 1.25rem;
    z-index: 9999; display: flex; flex-direction: column; gap: .5rem;
  }
  .toast-item {
    display: flex; align-items: center; gap: .75rem;
    padding: .85rem 1rem; border-radius: 12px;
    font-size: .875rem; font-weight: 600;
    box-shadow: 0 8px 24px -4px rgba(0,0,0,.15);
    min-width: 260px; max-width: 340px;
    animation: toastIn .3s cubic-bezier(.22,.68,0,1.2);
  }
  .toast-success { background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; }
  .toast-error   { background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; }
  .toast-info    { background: var(--sm-light); border: 1.5px solid var(--sm-accent); color: var(--sm-dark); }
  @keyframes toastIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:translateX(0)} }

  /* ── Bottom nav (mobile authenticated) ───────────────── */
  .bottom-nav {
    display: none;
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 90;
    background: var(--sm-white);
    border-top: 1.5px solid var(--sm-border);
    padding-bottom: env(safe-area-inset-bottom);
  }
  /* ── Burger : jamais sur PC (uniquement fenêtre < 1024px) ── */
  @media (min-width: 1024px) {
    .navbar-burger { display: none !important; }
  }
  @media (max-width: 1023px) {
    .navbar-burger { display: flex; }
  }

  @media(max-width: 1023px) { .bottom-nav { display: grid; grid-template-columns: repeat(4,1fr); } }
  .bottom-nav-item {
    display: flex; flex-direction: column; align-items: center;
    gap: .2rem; padding: .6rem .25rem .5rem;
    font-size: .65rem; font-weight: 700; color: var(--sm-muted);
    text-decoration: none; transition: color .15s;
  }
  .bottom-nav-item svg { width: 22px; height: 22px; }
  .bottom-nav-item.active { color: var(--sm-primary); }
  .bottom-nav-item.active svg { stroke: var(--sm-primary); }

  /* ── Footer ───────────────────────────────────────────── */
  .footer { background: #0d2726; }
  .footer-link { color: #94a3b8; font-size: .875rem; text-decoration: none; transition: color .15s; }
  .footer-link:hover { color: var(--sm-accent); }
  .footer-head { color: #fff; font-size: .75rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 1.1rem; }
  .social-btn {
    width: 36px; height: 36px; border-radius: 8px;
    background: rgba(255,255,255,.08); display: flex;
    align-items: center; justify-content: center;
    transition: background .15s;
  }
  .social-btn:hover { background: rgba(110,196,185,.2); }

  @media(max-width: 1023px) { main { padding-bottom: 68px; } }
  </style>
</head>

<body style="background:#f4f7f6" x-data x-init="$nextTick(() => { $store.notifications?.init?.() })">

  {{-- ═══════ MAIN NAVBAR ═════════════════════════════════ --}}
  <header class="sm-navbar" x-data="{ scrolled: false, mobileOpen: false }"
          x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })"
          :class="scrolled ? 'scrolled' : ''">
    <nav class="container mx-auto px-6 lg:px-20">
      <div class="flex items-center justify-between h-16 gap-4">

        {{-- LOGO --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2 flex-shrink-0">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:var(--sm-primary)">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <span class="text-xl font-extrabold" style="color:var(--sm-text)">
            Serve<span style="color:var(--sm-primary)">Me</span><span style="color:var(--sm-accent)">.</span>
          </span>
        </a>

        {{-- DESKTOP LINKS (cachés quand fenêtre petite = mode téléphone) --}}
        <div class="hidden lg:flex items-center gap-7">
          <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Accueil</a>
          <a href="{{ url('/about') }}" class="nav-link {{ request()->is('about') ? 'active' : '' }}">À propos</a>

          {{-- Services dropdown --}}
          <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
            <button class="nav-link flex items-center gap-1" :class="open ? 'active' : ''">
              Services
              <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="sm-dropdown">
              @foreach([
                ['🔧','Mécanique & Auto','mecanique'],
                ['🚿','Plomberie','plomberie'],
                ['⚡','Électricité','electricite'],
                ['🏠','Services à domicile','domicile'],
                ['🎓','Cours particuliers','education'],
                ['💼','Services entreprise','entreprise'],
              ] as [$icon,$label,$slug])
              <a href="{{ url('/services?category='.$slug) }}" class="sm-dropdown-item">
                <span class="text-base">{{ $icon }}</span>{{ $label }}
              </a>
              @endforeach
            </div>
          </div>

          <a href="{{ url('/blog') }}" class="nav-link {{ request()->is('blog*') ? 'active' : '' }}">Blog</a>
          <a href="{{ url('/contact') }}" class="nav-link {{ request()->is('contact') ? 'active' : '' }}">Contact</a>
        </div>

        {{-- DESKTOP RIGHT (recherche + auth, cachés quand fenêtre petite) --}}
        <div class="hidden lg:flex items-center gap-4">

        {{-- Zone recherche organisée --}}
        <div class="search-bar-wrap">
          <form action="{{ url('/services') }}" method="GET" role="search" class="google-like-search">
            <label for="nav-search-q" class="sr-only"></label>
            <input id="nav-search-q" type="search" name="q" placeholder="Rechercher un service"
                   autocomplete="off"
                   aria-label="Recherche">
            <svg class="search-icon w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </form>
        </div>

          @auth
          {{-- Notifications --}}
          <div x-data="{ open: false }" class="relative">
            <button @click="open=!open" @click.outside="open=false" class="icon-btn">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
              </svg>
              <span x-show="$store.notifications?.unread > 0" x-cloak class="notif-badge"
                    x-text="$store.notifications?.unread > 9 ? '9+' : $store.notifications?.unread"></span>
            </button>
            <div x-show="open" x-cloak x-transition
                 class="absolute right-0 top-full mt-2 w-80 bg-white rounded-xl shadow-xl border overflow-hidden z-50"
                 style="border-color:var(--sm-border)">
              <div class="flex items-center justify-between px-4 py-3 border-b" style="border-color:var(--sm-border)">
                <span class="text-sm font-bold" style="color:var(--sm-text)">Notifications</span>
                <button @click="$store.notifications?.markAllRead()" class="text-xs font-semibold" style="color:var(--sm-primary)">Tout lire</button>
              </div>
              <div class="max-h-72 overflow-y-auto">
                <template x-if="!$store.notifications?.items?.length">
                  <p class="text-center text-sm py-8" style="color:var(--sm-muted)">Aucune notification</p>
                </template>
                <template x-for="notif in $store.notifications?.items ?? []" :key="notif.id">
                  <div class="px-4 py-3 border-b last:border-0 hover:bg-slate-50" style="border-color:var(--sm-border)">
                    <p class="text-sm font-semibold" style="color:var(--sm-text)" x-text="notif.message"></p>
                    <p class="text-xs mt-0.5" style="color:var(--sm-muted)" x-text="notif.created_at"></p>
                  </div>
                </template>
              </div>
            </div>
          </div>

          {{-- User menu --}}
          <div x-data="{ open: false }" class="relative">
            <button @click="open=!open" @click.outside="open=false" class="user-menu-trigger">
              <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1F6E6C&color=fff' }}"
                   class="user-avatar" alt="{{ auth()->user()->name }}">
              <div class="hidden xl:block text-left">
                <p class="text-xs font-bold leading-tight" style="color:var(--sm-text)">{{ auth()->user()->name }}</p>
                <p class="text-xs" style="color:var(--sm-muted)">{{ ucfirst(auth()->user()->role ?? 'client') }}</p>
              </div>
              <svg class="w-3.5 h-3.5 ml-1 transition-transform" :class="open ? 'rotate-180' : ''" style="color:var(--sm-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-cloak x-transition class="sm-dropdown w-52" style="left:auto;right:0;transform:none">
              <a href="{{ route('dashboard') }}" class="sm-dropdown-item">🏠 Tableau de bord</a>
              <a href="{{ route('profile.edit') }}" class="sm-dropdown-item">👤 Mon profil</a>
              <a href="{{ route('reservations.index') }}" class="sm-dropdown-item">📋 Mes réservations</a>
              @if(auth()->user()->role === 'provider')
              <a href="{{ route('provider.dashboard') }}" class="sm-dropdown-item">🔧 Espace prestataire</a>
              @endif
              <div style="height:1px;background:var(--sm-border);margin:.4rem .5rem"></div>
              <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="sm-dropdown-item w-full text-left" style="color:#ef4444">
                  🚪 Déconnexion
                </button>
              </form>
            </div>
          </div>

          @else
          {{-- Guest auth buttons --}}
          <a href="{{ route('login') }}" class="btn-login">Connexion</a>
          <a href="{{ route('register') }}" class="btn-register">S'inscrire</a>
          @endauth
        </div>

        {{-- BURGER : visible seulement quand la fenêtre est petite (< 1024px), jamais sur PC ──}}
        <button @click="mobileOpen=!mobileOpen" class="navbar-burger lg:hidden icon-btn" aria-label="Menu">
          <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      {{-- MENU DÉROULANT (quand fenêtre petite, pas sur PC) --}}
      <div x-show="mobileOpen" x-cloak x-collapse class="mobile-nav lg:hidden">

        {{-- Recherche mobile organisée --}}
        <div class="search-bar-wrap w-full mb-3">
          <form action="{{ url('/services') }}" method="GET" role="search" class="google-like-search w-full max-w-none">
            <label for="mobile-search-q" class="sr-only">Rechercher un service</label>
            <input id="mobile-search-q" type="search" name="q" placeholder="Rechercher un service"
                   aria-label="Recherche">
            <svg class="search-icon w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </form>
        </div>

        <div class="mobile-divider"></div>

        <a href="{{ url('/') }}" class="mobile-nav-link {{ request()->is('/') ? 'active' : '' }}">🏠 Accueil</a>
        <a href="{{ url('/about') }}" class="mobile-nav-link">ℹ️ À propos</a>
        <a href="{{ url('/services') }}" class="mobile-nav-link {{ request()->is('services*') ? 'active' : '' }}">🔧 Services</a>
        <a href="{{ url('/blog') }}" class="mobile-nav-link {{ request()->is('blog*') ? 'active' : '' }}">📰 Blog</a>
        <a href="{{ url('/contact') }}" class="mobile-nav-link {{ request()->is('contact') ? 'active' : '' }}">📞 Contact</a>

        <div class="mobile-divider"></div>

        @guest
        <div class="flex gap-2 pt-1">
          <a href="{{ route('login') }}" class="btn-login flex-1 justify-center">Connexion</a>
          <a href="{{ route('register') }}" class="btn-register flex-1 justify-center">S'inscrire</a>
        </div>
        @else
        <div class="flex items-center gap-3 px-2 py-2 rounded-xl mb-2" style="background:var(--sm-light)">
          <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1F6E6C&color=fff' }}"
               class="user-avatar" alt="{{ auth()->user()->name }}">
          <div>
            <p class="text-sm font-bold" style="color:var(--sm-text)">{{ auth()->user()->name }}</p>
            <p class="text-xs" style="color:var(--sm-muted)">{{ auth()->user()->email }}</p>
          </div>
        </div>
        <a href="{{ route('dashboard') }}" class="mobile-nav-link">🏠 Tableau de bord</a>
        <a href="{{ route('profile.edit') }}" class="mobile-nav-link">👤 Mon profil</a>
        <a href="{{ route('reservations.index') }}" class="mobile-nav-link">📋 Mes réservations</a>
        <form method="POST" action="{{ route('logout') }}" class="mt-1">@csrf
          <button type="submit" class="mobile-nav-link w-full text-left" style="color:#ef4444">🚪 Déconnexion</button>
        </form>
        @endauth
      </div>
    </nav>
  </header>

  {{-- Flash messages --}}
  @if(session('success'))
  <div x-data="{ show: true }" x-init="setTimeout(()=>show=false,4000)" x-show="show" x-transition
       class="toast-wrap"><div class="toast-item toast-success">✓ {{ session('success') }}</div></div>
  @endif
  @if(session('error'))
  <div x-data="{ show: true }" x-init="setTimeout(()=>show=false,5000)" x-show="show" x-transition
       class="toast-wrap"><div class="toast-item toast-error">✕ {{ session('error') }}</div></div>
  @endif

  <main class="min-h-screen">
    @yield('content')
  </main>

  {{-- ═══════ FOOTER ══════════════════════════════════════ --}}
  <footer class="footer text-slate-400">
    <div class="container mx-auto px-6 lg:px-20 py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

        {{-- Brand --}}
        <div>
          <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-5">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:var(--sm-primary)">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="text-xl font-extrabold text-white">Serve<span style="color:var(--sm-accent)">Me</span>.</span>
          </a>
          <p class="text-sm leading-relaxed mb-5">Trouvez des prestataires de confiance près de vous instantanément. Disponible 24h/7j.</p>
          <div class="flex gap-2">
            @foreach([
              ['Facebook','M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
              ['Instagram','M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z'],
              ['LinkedIn','M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 110-4.124 2.062 2.062 0 010 4.124zM7.119 20.452H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'],
            ] as [$label,$path])
            <a href="#" aria-label="{{ $label }}" class="social-btn" style="color:var(--sm-accent)">
              <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="{{ $path }}"/></svg>
            </a>
            @endforeach
          </div>
        </div>

        {{-- Navigation --}}
        <div>
          <h4 class="footer-head">Navigation</h4>
          <ul class="space-y-2.5">
            @foreach(['Accueil'=>'/','À propos'=>'/about','Services'=>'/services','Blog'=>'/blog','Contact'=>'/contact'] as $label=>$path)
            <li><a href="{{ url($path) }}" class="footer-link flex items-center gap-2">
              <svg class="w-3 h-3 flex-shrink-0" style="color:var(--sm-accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
              {{ $label }}
            </a></li>
            @endforeach
          </ul>
        </div>

        {{-- Services --}}
        <div>
          <h4 class="footer-head">Nos Services</h4>
          <ul class="space-y-2.5">
            @foreach(['Mécanique & Auto','Plomberie','Électricité','Services à domicile','Cours particuliers'] as $s)
            <li><a href="{{ url('/services') }}" class="footer-link flex items-center gap-2">
              <svg class="w-3 h-3 flex-shrink-0" style="color:var(--sm-accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
              {{ $s }}
            </a></li>
            @endforeach
          </ul>
        </div>

        {{-- Contact --}}
        <div>
          <h4 class="footer-head">Contact</h4>
          <ul class="space-y-4 text-sm">
            <li class="flex items-start gap-3">
              <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:var(--sm-accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              Casablanca, Maroc
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-4 h-4 flex-shrink-0" style="color:var(--sm-accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <a href="mailto:contact@serveme.ma" class="footer-link">contact@serveme.ma</a>
            </li>
            <li class="flex items-center gap-3">
              <svg class="w-4 h-4 flex-shrink-0" style="color:var(--sm-accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              <a href="tel:+212600000000" class="footer-link">+212 600 000 000</a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div style="border-top:1px solid rgba(255,255,255,.07)">
      <div class="container mx-auto px-6 lg:px-20 py-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <p>© {{ date('Y') }} <span class="text-white font-bold">ServeMe</span>. Tous droits réservés.</p>
        <div class="flex gap-5">
          <a href="#" class="footer-link">Politique de confidentialité</a>
          <a href="#" class="footer-link">Conditions d'utilisation</a>
        </div>
      </div>
    </div>
  </footer>

  {{-- Mobile bottom nav (authenticated only) --}}
  @auth
  <nav class="bottom-nav">
    <a href="{{ url('/') }}" class="bottom-nav-item {{ request()->is('/') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      Accueil
    </a>
    <a href="{{ url('/services') }}" class="bottom-nav-item {{ request()->is('services*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      Services
    </a>
    <a href="{{ route('reservations.index') }}" class="bottom-nav-item {{ request()->is('reservations*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Réservations
    </a>
    <a href="{{ route('profile.edit') }}" class="bottom-nav-item {{ request()->is('profile*') ? 'active' : '' }}">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      Profil
    </a>
  </nav>
  @endauth

  {{-- Global toast system --}}
  <div class="toast-wrap" x-data aria-live="polite">
    <template x-for="item in $store.toast?.items ?? []" :key="item.id">
      <div :class="{ 'toast-success': item.type==='success', 'toast-error': item.type==='error', 'toast-info': item.type==='info' }" class="toast-item">
        <span x-text="item.type==='success' ? '✓' : item.type==='error' ? '✕' : 'ℹ'"></span>
        <span x-text="item.message" class="flex-1"></span>
        <button @click="$store.toast?.dismiss(item.id)" class="opacity-60 hover:opacity-100 text-lg leading-none ml-2">×</button>
      </div>
    </template>
  </div>

  @livewireScripts
  @stack('scripts')
</body>
</html>