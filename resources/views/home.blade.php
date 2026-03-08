@extends('layouts.app')

@section('title', 'Accueil')

@push('styles')
<style>
/* =========================================================
   SERVEME — DESIGN SYSTEM (uses layout --sm-* tokens)
   ========================================================= */

/* ── Hero ───────────────────────────────────────────────── */
.sm-hero {
  background: linear-gradient(135deg, var(--sm-dark) 0%, var(--sm-primary) 55%, var(--sm-mid) 100%);
  position: relative;
  overflow: hidden;
}
.sm-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(ellipse 80% 60% at 70% 50%, rgba(110,196,185,.12) 0%, transparent 70%),
    url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236EC4B9' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  pointer-events: none;
}

/* ── Wave divider ───────────────────────────────────────── */
.wave-divider { line-height: 0; }
.wave-divider svg { animation: waveFloat 4s ease-in-out infinite; will-change: transform; }
@keyframes waveFloat {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(-4px); }
}

/* ── Stats banner ───────────────────────────────────────── */
.sm-stats-bar { background: var(--sm-primary); }
.hero-stats-row { animation: heroStatsFloat 5s ease-in-out infinite; will-change: transform; }
@keyframes heroStatsFloat {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(-4px); }
}
.hero-stats-row .text-2xl { animation: heroStatPulse 2.5s ease-in-out infinite; }
.hero-stats-row > div:nth-child(1) .text-2xl { animation-delay: 0s; }
.hero-stats-row > div:nth-child(3) .text-2xl { animation-delay: .15s; }
.hero-stats-row > div:nth-child(5) .text-2xl { animation-delay: .3s; }
@keyframes heroStatPulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%      { opacity: .92; transform: scale(1.04); }
}
.sm-stat-item { border-right: 1px solid rgba(255,255,255,.15); }
.sm-stat-item:last-child { border-right: none; }

/* ── Section labels ─────────────────────────────────────── */
.sm-tag {
  display: inline-flex; align-items: center; gap: .4rem;
  font-size: .75rem; font-weight: 700; letter-spacing: .08em;
  text-transform: uppercase; color: var(--sm-primary);
  background: var(--sm-light); padding: .3rem .75rem;
  border-radius: 999px; margin-bottom: .75rem;
}
.sm-title { font-size: clamp(1.6rem, 3vw, 2.4rem); font-weight: 800; color: var(--sm-text); line-height: 1.2; }

/* ── Category cards ─────────────────────────────────────── */
.cat-tile {
  background: #fff; border: 1.5px solid var(--sm-border); border-radius: 16px;
  padding: 1.25rem .75rem; text-align: center; cursor: pointer;
  transition: transform .2s, box-shadow .2s, border-color .2s;
}
.cat-tile:hover { transform: translateY(-4px); box-shadow: 0 12px 32px -8px rgba(31,110,108,.18); border-color: var(--sm-accent); }
.cat-tile .icon-wrap {
  width: 52px; height: 52px; background: var(--sm-light); border-radius: 14px;
  display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
  margin: 0 auto .75rem; transition: background .2s;
}
.cat-tile:hover .icon-wrap { background: var(--sm-accent); }

/* ── Steps ──────────────────────────────────────────────── */
.step-card {
  background: #fff; border: 1.5px solid var(--sm-border); border-radius: 20px;
  padding: 1.75rem 1.5rem 1.5rem; position: relative;
}
.step-badge {
  position: absolute; top: -14px; left: 20px;
  width: 28px; height: 28px; border-radius: 50%;
  background: var(--sm-primary); color: #fff; font-size: .8rem; font-weight: 800;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 3px 10px rgba(31,110,108,.35);
}

/* ── Provider cards (section vedette) ───────────────────── */
.pro-card {
  background: #fff; border: 1.5px solid var(--sm-border); border-radius: 20px;
  overflow: hidden; transition: transform .2s, box-shadow .2s;
}
.pro-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px -12px rgba(31,110,108,.2); }

/* ── Ping dot ───────────────────────────────────────────── */
@keyframes ping { 75%,100%{transform:scale(2);opacity:0} }
.ping-dot span:first-child { animation: ping 1.2s cubic-bezier(0,0,.2,1) infinite; }

/* ── Role CTA ───────────────────────────────────────────── */
.role-client { background: linear-gradient(135deg, var(--sm-primary), var(--sm-dark)); border-radius: 24px; padding: 2.5rem 2rem; color: #fff; }
.role-provider { background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 24px; padding: 2.5rem 2rem; color: #fff; }

/* ── Urgency banner ─────────────────────────────────────── */
.urgency-bar { background: linear-gradient(90deg, #dc2626, #b91c1c); border-radius: 16px; padding: 1.1rem 1.5rem; }

/* ── Testimonials ───────────────────────────────────────── */
.testi-card { background: #fff; border: 1.5px solid var(--sm-border); border-radius: 20px; padding: 1.5rem; }

/* ── Reveal animation ───────────────────────────────────── */
@keyframes revealUp { from { opacity:0; transform:translateY(28px); } to { opacity:1; transform:translateY(0); } }
.reveal { animation: revealUp .55s cubic-bezier(.22,.68,0,1.2) both; }
.d1 { animation-delay:.05s; } .d2 { animation-delay:.15s; } .d3 { animation-delay:.25s; } .d4 { animation-delay:.35s; }

/* ── Buttons ────────────────────────────────────────────── */
.btn-sm-primary {
  display: inline-flex; align-items: center; gap: .5rem; background: var(--sm-primary);
  color: #fff; font-weight: 700; font-size: .875rem; padding: .7rem 1.4rem; border-radius: 10px;
  transition: background .2s, transform .15s; text-decoration: none;
}
.btn-sm-primary:hover { background: var(--sm-dark); transform: translateY(-1px); }
.btn-sm-outline {
  display: inline-flex; align-items: center; gap: .5rem; border: 2px solid var(--sm-primary);
  color: var(--sm-primary); font-weight: 700; font-size: .875rem; padding: .65rem 1.35rem;
  border-radius: 10px; transition: background .2s, color .2s; text-decoration: none; background: transparent;
}
.btn-sm-outline:hover { background: var(--sm-primary); color: #fff; }

/* ── Pill badge ─────────────────────────────────────────── */
.badge-top { background: var(--sm-primary); color:#fff; font-size:.7rem; font-weight:700; padding:.25rem .6rem; border-radius:999px; }

/* ── CTA newsletter ─────────────────────────────────────── */
.nl-input { flex: 1; padding: .75rem 1rem; border-radius: 10px; border: none; outline: none; font-size: .875rem; color: var(--sm-text); }
.nl-btn { background: var(--sm-accent); color: var(--sm-dark); font-weight: 700; font-size: .875rem; padding: .75rem 1.2rem; border-radius: 10px; border: none; cursor: pointer; transition: background .2s; }
.nl-btn:hover { background: #56b5aa; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════
     1. HERO
     ═══════════════════════════════════════════════════════ --}}
<section id="hero" class="sm-hero min-h-[85vh] lg:min-h-[90vh] flex items-center py-16 lg:py-24 relative" aria-labelledby="hero-title">
  <div class="container mx-auto px-6 lg:px-20 relative z-10">
    <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

      {{-- ── LEFT : Texte + stats ── --}}
      <div class="text-white reveal d1">
        <h1 id="hero-title" class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-[1.08] mb-6 tracking-tight">
          Le bon<br>prestataire,<br>
          <span style="color:var(--sm-accent);">en quelques clics.</span>
        </h1>
        <p class="text-lg text-white/80 mb-10 max-w-md leading-relaxed">
          Mécanicien, plombier, électricien, professeur… Intervention immédiate ou programmée, suivi en temps réel.
        </p>
        <div class="flex flex-wrap gap-3">
          <a href="{{ url('/services') }}" class="btn-sm-primary text-base px-7 py-3.5" style="background:var(--sm-accent);color:var(--sm-dark);">
            Trouver un service
          </a>
          @guest
          <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white/15 border border-white/30 text-white font-bold text-sm px-6 py-3.5 rounded-xl hover:bg-white/25 transition-all">
            S'inscrire gratuitement
          </a>
          @endguest
        </div>
        <div class="hero-stats-row flex items-center gap-6 mt-10 pt-8 border-t border-white/15">
          <div><p class="text-2xl font-extrabold">200+</p><p class="text-white/60 text-sm">Prestataires</p></div>
          <div class="w-px h-10 bg-white/20"></div>
          <div><p class="text-2xl font-extrabold">1 500+</p><p class="text-white/60 text-sm">Réservations</p></div>
          <div class="w-px h-10 bg-white/20"></div>
          <div><p class="text-2xl font-extrabold">98%</p><p class="text-white/60 text-sm">Satisfaits</p></div>
        </div>
      </div>

      {{-- ══════════════════════════════════════════════════
           ── RIGHT : Illustration SVG — services en orbite ──
           Remplace les 4 cartes prestataires
           ══════════════════════════════════════════════════ --}}
      <div class="hidden lg:flex lg:items-center lg:justify-center reveal d2">
        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 500 500"
             class="w-full max-w-[480px] h-auto"
             role="img"
             aria-label="ServeMe — plateforme de services à domicile">

          <defs>
            <linearGradient id="g-white-card" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#ffffff" stop-opacity="0.97"/>
              <stop offset="100%" stop-color="#eef9f8" stop-opacity="0.93"/>
            </linearGradient>
            <linearGradient id="g-teal-deep" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#134e4a"/><stop offset="100%" stop-color="#1F6E6C"/>
            </linearGradient>
            <linearGradient id="g-teal-mid" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#1F6E6C"/><stop offset="100%" stop-color="#2a8a87"/>
            </linearGradient>
            <linearGradient id="g-accent-svg" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#6EC4B9"/><stop offset="100%" stop-color="#4db3a7"/>
            </linearGradient>
            <linearGradient id="g-amber" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#f59e0b"/><stop offset="100%" stop-color="#fbbf24"/>
            </linearGradient>
            <linearGradient id="g-blue" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#2563eb"/><stop offset="100%" stop-color="#3b82f6"/>
            </linearGradient>
            <linearGradient id="g-rose" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#e11d48"/><stop offset="100%" stop-color="#f43f5e"/>
            </linearGradient>
            <linearGradient id="g-violet" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#7c3aed"/><stop offset="100%" stop-color="#8b5cf6"/>
            </linearGradient>
            <linearGradient id="g-green" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#16a34a"/><stop offset="100%" stop-color="#22c55e"/>
            </linearGradient>
            <linearGradient id="g-orange" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#ea580c"/><stop offset="100%" stop-color="#f97316"/>
            </linearGradient>
            <filter id="f-card" x="-15%" y="-15%" width="130%" height="140%">
              <feDropShadow dx="0" dy="10" stdDeviation="18" flood-color="#0d2726" flood-opacity="0.22"/>
            </filter>
            <filter id="f-icon" x="-20%" y="-20%" width="140%" height="150%">
              <feDropShadow dx="0" dy="5" stdDeviation="10" flood-color="#0d2726" flood-opacity="0.2"/>
            </filter>
            <filter id="f-glow" x="-30%" y="-30%" width="160%" height="160%">
              <feDropShadow dx="0" dy="0" stdDeviation="14" flood-color="#6EC4B9" flood-opacity="0.45"/>
            </filter>
            <filter id="f-blur-bg"><feGaussianBlur stdDeviation="30"/></filter>
          </defs>

          {{-- Halos de fond --}}
          <circle cx="250" cy="250" r="160" fill="#6EC4B9" fill-opacity="0.07" filter="url(#f-blur-bg)"/>
          <circle cx="340" cy="150" r="80"  fill="#6EC4B9" fill-opacity="0.05" filter="url(#f-blur-bg)"/>
          <circle cx="150" cy="370" r="70"  fill="#6EC4B9" fill-opacity="0.05" filter="url(#f-blur-bg)"/>

          {{-- Orbites pointillées animées --}}
          <circle cx="250" cy="250" r="178" fill="none"
                  stroke="rgba(110,196,185,0.22)" stroke-width="1.2" stroke-dasharray="6 5">
            <animateTransform attributeName="transform" type="rotate"
              from="0 250 250" to="360 250 250" dur="40s" repeatCount="indefinite"/>
          </circle>
          <circle cx="250" cy="250" r="130" fill="none"
                  stroke="rgba(110,196,185,0.14)" stroke-width="1" stroke-dasharray="4 6">
            <animateTransform attributeName="transform" type="rotate"
              from="360 250 250" to="0 250 250" dur="28s" repeatCount="indefinite"/>
          </circle>

          {{-- ═══ CENTRE — Logo ServeMe ═══ --}}
          <g filter="url(#f-card)">
            <circle cx="250" cy="250" r="88" fill="url(#g-white-card)"/>
            <circle cx="250" cy="250" r="88" fill="none" stroke="url(#g-accent-svg)" stroke-width="2.5" opacity="0.6"/>
            <g filter="url(#f-glow)">
              <polygon points="262,198 238,252 256,252 238,302 270,240 250,240 270,198" fill="url(#g-teal-mid)"/>
            </g>
            <text x="250" y="320" text-anchor="middle" font-size="17" font-weight="800" fill="#1e2d2c"
                  font-family="Plus Jakarta Sans, system-ui, sans-serif" letter-spacing="-0.3">
              Serve<tspan fill="#1F6E6C">Me</tspan><tspan fill="#6EC4B9">.</tspan>
            </text>
          </g>

          {{-- Lignes de connexion centre ↔ icônes --}}
          <g opacity="0.15" stroke="#6EC4B9" stroke-width="1" fill="none">
            <line x1="250" y1="162" x2="250" y2="106"/>
            <line x1="312" y1="188" x2="347" y2="153"/>
            <line x1="338" y1="250" x2="394" y2="250"/>
            <line x1="312" y1="312" x2="347" y2="347"/>
            <line x1="250" y1="338" x2="250" y2="394"/>
            <line x1="188" y1="312" x2="153" y2="347"/>
            <line x1="162" y1="250" x2="106" y2="250"/>
            <line x1="188" y1="188" x2="153" y2="153"/>
          </g>

          {{-- Points lumineux glissants sur les lignes --}}
          <g fill="#6EC4B9" opacity="0.75">
            <circle r="3" cx="250" cy="162">
              <animate attributeName="cy" values="162;106;162" dur="2s" repeatCount="indefinite"/>
              <animate attributeName="opacity" values="0.75;0;0.75" dur="2s" repeatCount="indefinite"/>
            </circle>
            <circle r="3" cx="338" cy="250">
              <animate attributeName="cx" values="338;394;338" dur="2.4s" repeatCount="indefinite"/>
              <animate attributeName="opacity" values="0.75;0;0.75" dur="2.4s" repeatCount="indefinite"/>
            </circle>
            <circle r="3" cx="250" cy="338">
              <animate attributeName="cy" values="338;394;338" dur="2.2s" repeatCount="indefinite"/>
              <animate attributeName="opacity" values="0.75;0;0.75" dur="2.2s" repeatCount="indefinite"/>
            </circle>
            <circle r="3" cx="162" cy="250">
              <animate attributeName="cx" values="162;106;162" dur="2.6s" repeatCount="indefinite"/>
              <animate attributeName="opacity" values="0.75;0;0.75" dur="2.6s" repeatCount="indefinite"/>
            </circle>
          </g>

          {{-- ═══ ① Plomberie — top (250, 72) ═══ --}}
          <g filter="url(#f-icon)"><g>
            <animateTransform attributeName="transform" type="translate"
              values="0,0;0,-10;0,-6;0,-12;0,0" keyTimes="0;0.25;0.5;0.75;1"
              dur="5.5s" repeatCount="indefinite" calcMode="spline"
              keySplines="0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1"/>
            <circle cx="250" cy="72" r="34" fill="url(#g-teal-mid)"/>
            <text x="250" y="79" text-anchor="middle" font-size="22">🔧</text>
            <rect x="220" y="110" width="60" height="20" rx="10" fill="rgba(255,255,255,0.15)"/>
            <text x="250" y="124" text-anchor="middle" font-size="9.5" font-weight="700"
                  fill="white" font-family="Plus Jakarta Sans, sans-serif">Plomberie</text>
          </g></g>

          {{-- ═══ ② Électricité — haut-droite (376, 124) ═══ --}}
          <g filter="url(#f-icon)"><g>
            <animateTransform attributeName="transform" type="translate"
              values="0,0;5,-8;2,-4;7,-10;0,0" keyTimes="0;0.25;0.5;0.75;1"
              dur="6.2s" repeatCount="indefinite" calcMode="spline"
              keySplines="0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1"/>
            <circle cx="376" cy="124" r="34" fill="url(#g-amber)"/>
            <text x="376" y="131" text-anchor="middle" font-size="22">⚡</text>
            <rect x="346" y="162" width="60" height="20" rx="10" fill="rgba(255,255,255,0.15)"/>
            <text x="376" y="176" text-anchor="middle" font-size="9.5" font-weight="700"
                  fill="white" font-family="Plus Jakarta Sans, sans-serif">Électricité</text>
          </g></g>

          {{-- ═══ ③ Mécanique — droite (428, 250) ═══ --}}
          <g filter="url(#f-icon)"><g>
            <animateTransform attributeName="transform" type="translate"
              values="0,0;9,0;5,0;11,0;0,0" keyTimes="0;0.25;0.5;0.75;1"
              dur="5s" repeatCount="indefinite" calcMode="spline"
              keySplines="0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1"/>
            <circle cx="428" cy="250" r="34" fill="url(#g-orange)"/>
            <text x="428" y="257" text-anchor="middle" font-size="22">🔩</text>
            <rect x="398" y="288" width="60" height="20" rx="10" fill="rgba(255,255,255,0.15)"/>
            <text x="428" y="302" text-anchor="middle" font-size="9.5" font-weight="700"
                  fill="white" font-family="Plus Jakarta Sans, sans-serif">Mécanique</text>
          </g></g>

          {{-- ═══ ④ Domicile — bas-droite (376, 376) ═══ --}}
          <g filter="url(#f-icon)"><g>
            <animateTransform attributeName="transform" type="translate"
              values="0,0;6,8;3,4;8,10;0,0" keyTimes="0;0.25;0.5;0.75;1"
              dur="7s" repeatCount="indefinite" calcMode="spline"
              keySplines="0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1"/>
            <circle cx="376" cy="376" r="34" fill="url(#g-blue)"/>
            <text x="376" y="383" text-anchor="middle" font-size="22">🏠</text>
            <rect x="346" y="344" width="60" height="20" rx="10" fill="rgba(255,255,255,0.15)"/>
            <text x="376" y="358" text-anchor="middle" font-size="9.5" font-weight="700"
                  fill="white" font-family="Plus Jakarta Sans, sans-serif">Domicile</text>
          </g></g>

          {{-- ═══ ⑤ Peinture — bas (250, 428) ═══ --}}
          <g filter="url(#f-icon)"><g>
            <animateTransform attributeName="transform" type="translate"
              values="0,0;0,10;0,6;0,12;0,0" keyTimes="0;0.25;0.5;0.75;1"
              dur="6s" repeatCount="indefinite" calcMode="spline"
              keySplines="0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1"/>
            <circle cx="250" cy="428" r="34" fill="url(#g-rose)"/>
            <text x="250" y="435" text-anchor="middle" font-size="22">🎨</text>
            <rect x="220" y="388" width="60" height="20" rx="10" fill="rgba(255,255,255,0.15)"/>
            <text x="250" y="402" text-anchor="middle" font-size="9.5" font-weight="700"
                  fill="white" font-family="Plus Jakarta Sans, sans-serif">Peinture</text>
          </g></g>

          {{-- ═══ ⑥ Jardinage — bas-gauche (124, 376) ═══ --}}
          <g filter="url(#f-icon)"><g>
            <animateTransform attributeName="transform" type="translate"
              values="0,0;-6,7;-3,4;-8,9;0,0" keyTimes="0;0.25;0.5;0.75;1"
              dur="5.8s" repeatCount="indefinite" calcMode="spline"
              keySplines="0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1"/>
            <circle cx="124" cy="376" r="34" fill="url(#g-green)"/>
            <text x="124" y="383" text-anchor="middle" font-size="22">🌿</text>
            <rect x="94" y="344" width="60" height="20" rx="10" fill="rgba(255,255,255,0.15)"/>
            <text x="124" y="358" text-anchor="middle" font-size="9.5" font-weight="700"
                  fill="white" font-family="Plus Jakarta Sans, sans-serif">Jardinage</text>
          </g></g>

          {{-- ═══ ⑦ Rénovation — gauche (72, 250) ═══ --}}
          <g filter="url(#f-icon)"><g>
            <animateTransform attributeName="transform" type="translate"
              values="0,0;-9,0;-5,0;-11,0;0,0" keyTimes="0;0.25;0.5;0.75;1"
              dur="6.5s" repeatCount="indefinite" calcMode="spline"
              keySplines="0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1"/>
            <circle cx="72" cy="250" r="34" fill="url(#g-violet)"/>
            <text x="72" y="257" text-anchor="middle" font-size="22">💡</text>
            <rect x="42" y="288" width="60" height="20" rx="10" fill="rgba(255,255,255,0.15)"/>
            <text x="72" y="302" text-anchor="middle" font-size="9.5" font-weight="700"
                  fill="white" font-family="Plus Jakarta Sans, sans-serif">Rénovation</text>
          </g></g>

          {{-- ═══ ⑧ Éducation — haut-gauche (124, 124) ═══ --}}
          <g filter="url(#f-icon)"><g>
            <animateTransform attributeName="transform" type="translate"
              values="0,0;-5,-8;-2,-4;-7,-10;0,0" keyTimes="0;0.25;0.5;0.75;1"
              dur="4.8s" repeatCount="indefinite" calcMode="spline"
              keySplines="0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1;0.4 0 0.6 1"/>
            <circle cx="124" cy="124" r="34" fill="url(#g-teal-deep)"/>
            <text x="124" y="131" text-anchor="middle" font-size="22">🎓</text>
            <rect x="94" y="162" width="60" height="20" rx="10" fill="rgba(255,255,255,0.15)"/>
            <text x="124" y="176" text-anchor="middle" font-size="9.5" font-weight="700"
                  fill="white" font-family="Plus Jakarta Sans, sans-serif">Éducation</text>
          </g></g>

        </svg>
      </div>
      {{-- ══ FIN RIGHT ══ --}}

    </div>
  </div>

  <div class="wave-divider absolute bottom-0 left-0 right-0">
    <svg viewBox="0 0 1440 72" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 36C360 72 1080 0 1440 36V72H0V36Z" fill="#f4f7f6"/>
    </svg>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     2. STATS
     ═══════════════════════════════════════════════════════ --}}
<section id="stats" class="sm-stats-bar py-0" aria-label="Chiffres clés">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-white/10">
      @foreach([['200+','Prestataires actifs','👷'],['1 500+','Réservations réalisées','📋'],['98%','Clients satisfaits','⭐'],['~15 min','Temps de réponse','⚡']] as [$val,$label,$icon])
      <div class="sm-stat-item text-center py-8 px-4">
        <div class="text-2xl mb-1">{{ $icon }}</div>
        <div class="text-3xl font-extrabold text-white leading-none">{{ $val }}</div>
        <p class="text-sm mt-1 text-white/60">{{ $label }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     3. URGENCE
     ═══════════════════════════════════════════════════════ --}}
<section id="urgence" class="py-8 lg:py-10 bg-[var(--sm-slate)]">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="urgency-bar flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">🚨</div>
        <div class="text-white">
          <p class="font-bold text-lg leading-tight">Intervention urgente ?</p>
          <p class="text-red-100 text-sm">Prestataires disponibles maintenant · Réponse &lt; 15 min</p>
        </div>
      </div>
      <a href="{{ url('/services?type=immediate') }}" class="bg-white text-red-600 font-bold text-sm px-6 py-2.5 rounded-full hover:bg-red-50 transition-colors flex-shrink-0">
        Demande urgente
      </a>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     4. CATÉGORIES
     ═══════════════════════════════════════════════════════ --}}
<section id="categories" class="py-16 lg:py-20 bg-[var(--sm-slate)]" aria-labelledby="categories-title">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="text-center mb-10 lg:mb-12 reveal">
      <span class="sm-tag">Nos catégories</span>
      <h2 id="categories-title" class="sm-title">Tous les services dont vous avez besoin</h2>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 max-w-6xl mx-auto">
      @php
      $cats = [
        ['🔧','Mécanique','34 pros','mecanique'],
        ['🚿','Plomberie','28 pros','plomberie'],
        ['⚡','Électricité','22 pros','electricite'],
        ['🏠','Domicile','45 pros','domicile'],
        ['🎓','Éducation','31 pros','education'],
        ['💊','Santé','18 pros','sante'],
        ['🎨','Peinture','15 pros','peinture'],
        ['🌿','Jardinage','12 pros','jardinage'],
        ['💼','Entreprise','16 pros','entreprise'],
        ['🛠','Menuiserie','9 pros','menuiserie'],
      ];
      @endphp
      @foreach($cats as $i => [$icon,$label,$count,$slug])
      <a href="{{ url('/services?category='.$slug) }}"
         class="cat-tile reveal {{ ['d1','d2','d3','d4','d1'][$i%5] }}">
        <div class="icon-wrap">{{ $icon }}</div>
        <p class="text-sm font-bold" style="color:var(--sm-text)">{{ $label }}</p>
        <p class="text-xs mt-0.5" style="color:var(--sm-muted)">{{ $count }}</p>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     5. COMMENT ÇA MARCHE
     ═══════════════════════════════════════════════════════ --}}
<section id="comment-ca-marche" class="py-16 lg:py-20 bg-white" aria-labelledby="steps-title">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="text-center mb-12 lg:mb-14 reveal">
      <span class="sm-tag">Simple & rapide</span>
      <h2 id="steps-title" class="sm-title">Réservez en 3 étapes</h2>
    </div>
    <div class="mb-12">
      <div class="inline-flex items-center gap-2 mb-8">
        <span class="px-3 py-1 rounded-full text-white text-xs font-bold" style="background:var(--sm-primary)">👤 Client</span>
      </div>
      <div class="grid md:grid-cols-3 gap-6">
        @foreach([
          ['1','🔍','Choisissez un service','Sélectionnez la catégorie, entrez votre adresse et précisez si c\'est urgent ou programmé.'],
          ['2','📋','Réservez un prestataire','Consultez les profils, avis et disponibilités. Confirmez en un clic.'],
          ['3','✅','Suivez & évaluez','Suivez en temps réel puis laissez votre avis après l\'intervention.'],
        ] as $i => [$n,$ico,$title,$desc])
        <div class="step-card reveal d{{ $i+1 }}">
          <div class="step-badge">{{ $n }}</div>
          <div class="text-3xl mb-3 mt-1">{{ $ico }}</div>
          <h3 class="font-bold mb-2" style="color:var(--sm-text)">{{ $title }}</h3>
          <p class="text-sm leading-relaxed" style="color:var(--sm-muted)">{{ $desc }}</p>
        </div>
        @endforeach
      </div>
    </div>
    <div class="border-t my-10" style="border-color:var(--sm-border)"></div>
    <div>
      <div class="inline-flex items-center gap-2 mb-8">
        <span class="px-3 py-1 rounded-full text-white text-xs font-bold bg-slate-800">🔨 Prestataire</span>
      </div>
      <div class="grid md:grid-cols-3 gap-6">
        @foreach([
          ['1','📝','Créez votre profil','Inscrivez-vous, ajoutez vos compétences et définissez votre zone d\'intervention.'],
          ['2','📨','Recevez des demandes','Les clients proches vous contactent. Acceptez, refusez ou proposez un autre créneau.'],
          ['3','💰','Intervenez & soyez payé','Effectuez l\'intervention et recevez votre paiement sécurisé.'],
        ] as $i => [$n,$ico,$title,$desc])
        <div class="step-card reveal d{{ $i+1 }}" style="border-color:#e2e8f0">
          <div class="step-badge" style="background:#1e293b">{{ $n }}</div>
          <div class="text-3xl mb-3 mt-1">{{ $ico }}</div>
          <h3 class="font-bold mb-2" style="color:var(--sm-text)">{{ $title }}</h3>
          <p class="text-sm leading-relaxed" style="color:var(--sm-muted)">{{ $desc }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     6. PRESTATAIRES EN VEDETTE
     ═══════════════════════════════════════════════════════ --}}
<section id="prestataires" class="py-16 lg:py-20 bg-[var(--sm-slate)]" aria-labelledby="providers-title">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-10 lg:mb-12 reveal">
      <div>
        <span class="sm-tag">Professionnels vérifiés</span>
        <h2 id="providers-title" class="sm-title">Prestataires près de vous</h2>
      </div>
      <a href="{{ url('/services') }}" class="hidden md:inline-flex items-center gap-1 text-sm font-semibold" style="color:var(--sm-primary)">Voir tous</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @php
      $providers = [
        [1,'Karim Mansouri','Plombier',4.9,127,'1.2 km','1F6E6C','Top'],
        [2,'Fatima Zahra','Électricienne',4.8,89,'2.1 km','7c3aed',''],
        [3,'Youssef Alami','Mécanicien',4.7,214,'0.8 km','d97706','Rapide'],
        [4,'Sara Benali','Professeure',5.0,56,'3.4 km','db2777',''],
      ];
      @endphp
      @foreach($providers as $i => [$id,$name,$service,$rating,$reviews,$dist,$color,$badge])
      <div class="pro-card reveal d{{ $i+1 }}">
        <div class="relative p-5 pb-14" style="background:linear-gradient(135deg,#f0faf9,#e8f5f4)">
          @if($badge)<span class="absolute top-3 right-3 badge-top">{{ $badge }}</span>@endif
          <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background={{ $color }}&color=fff&size=120"
               class="w-16 h-16 rounded-full border-4 border-white shadow-md" alt="{{ $name }}">
        </div>
        <div class="p-4 -mt-10 relative z-10">
          <div class="bg-white rounded-xl shadow-sm border p-3 mb-3" style="border-color:var(--sm-border)">
            <div class="flex items-start justify-between mb-1">
              <div>
                <h3 class="font-bold text-sm" style="color:var(--sm-text)">{{ $name }}</h3>
                <p class="text-xs" style="color:var(--sm-muted)">{{ $service }}</p>
              </div>
              <div class="ping-dot relative flex h-2 w-2 mt-1">
                <span class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-70"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-green-500"></span>
              </div>
            </div>
            <div class="flex items-center gap-3 text-xs" style="color:var(--sm-muted)">
              <span>⭐ <strong style="color:var(--sm-text)">{{ $rating }}</strong> ({{ $reviews }})</span>
              <span>·</span>
              <span>📍 {{ $dist }}</span>
            </div>
          </div>
          <div class="flex gap-2">
            <a href="{{ url('/providers/'.$id) }}" class="flex-1 text-center text-xs font-semibold py-2 rounded-lg border-2 transition-colors" style="color:var(--sm-primary);border-color:var(--sm-accent)">Profil</a>
            <a href="{{ url('/reservations/create?provider='.$id) }}" class="flex-1 text-center text-xs font-bold text-white py-2 rounded-lg transition-colors" style="background:var(--sm-primary)">Réserver</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     7. CTA CLIENT / PRESTATAIRE
     ═══════════════════════════════════════════════════════ --}}
<section id="rejoindre" class="py-16 lg:py-20 bg-white" aria-labelledby="role-cta-title">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="text-center mb-10 lg:mb-12 reveal">
      <span class="sm-tag">Rejoignez ServeMe</span>
      <h2 id="role-cta-title" class="sm-title">Pour chaque besoin, une solution</h2>
    </div>
    <div class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto">
      <div class="role-client reveal d1">
        <div class="text-4xl mb-4">👤</div>
        <h3 class="text-2xl font-extrabold mb-3">Vous cherchez un service ?</h3>
        <p class="text-white/75 mb-6 leading-relaxed">Trouvez le bon prestataire en quelques clics. Réservation immédiate ou programmée, suivi en temps réel, paiement sécurisé.</p>
        <ul class="space-y-2 mb-8">
          @foreach(['Prestataires vérifiés et notés','Réponse en moins de 15 min','Suivi GPS en temps réel','Paiement sécurisé'] as $item)
          <li class="flex items-center gap-2 text-sm text-white/80">
            <svg class="w-4 h-4 flex-shrink-0" style="color:var(--sm-accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>{{ $item }}
          </li>
          @endforeach
        </ul>
        @guest
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white font-bold px-6 py-3 rounded-full text-sm hover:bg-slate-100 transition-colors" style="color:var(--sm-dark)">S'inscrire gratuitement</a>
        @else
        <a href="{{ url('/services') }}" class="inline-flex items-center gap-2 bg-white font-bold px-6 py-3 rounded-full text-sm hover:bg-slate-100 transition-colors" style="color:var(--sm-dark)">Trouver un service</a>
        @endauth
      </div>
      <div class="role-provider reveal d2">
        <div class="text-4xl mb-4">🔨</div>
        <h3 class="text-2xl font-extrabold mb-3 text-white">Vous êtes prestataire ?</h3>
        <p class="text-slate-400 mb-6 leading-relaxed">Rejoignez notre réseau et développez votre clientèle. Recevez des demandes, gérez votre planning et augmentez vos revenus.</p>
        <ul class="space-y-2 mb-8">
          @foreach(['Inscription gratuite','Clients qualifiés près de vous','Gestion de planning intégrée','Paiements rapides et sécurisés'] as $item)
          <li class="flex items-center gap-2 text-sm text-slate-400">
            <svg class="w-4 h-4 flex-shrink-0 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>{{ $item }}
          </li>
          @endforeach
        </ul>
        @guest
        <a href="{{ route('register') }}?role=provider" class="inline-flex items-center gap-2 font-bold px-6 py-3 rounded-full text-sm transition-colors" style="background:var(--sm-accent);color:var(--sm-dark)">Devenir prestataire</a>
        @else
        @if(auth()->user()->role === 'provider')
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 font-bold px-6 py-3 rounded-full text-sm transition-colors" style="background:var(--sm-accent);color:var(--sm-dark)">Mon tableau de bord</a>
        @else
        <a href="{{ route('register') }}?role=provider" class="inline-flex items-center gap-2 font-bold px-6 py-3 rounded-full text-sm transition-colors" style="background:var(--sm-accent);color:var(--sm-dark)">Devenir prestataire</a>
        @endif
        @endauth
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     8. CARTE / LOCALISATION
     ═══════════════════════════════════════════════════════ --}}
<section id="carte" class="py-16 lg:py-20 bg-[var(--sm-slate)]" aria-labelledby="map-title">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
      <div class="reveal d1">
        <span class="sm-tag">📍 Localisation</span>
        <h2 id="map-title" class="sm-title mb-5">Prestataires proches en temps réel</h2>
        <p class="leading-relaxed mb-6 text-sm" style="color:var(--sm-muted)">Notre carte interactive affiche les prestataires disponibles dans votre zone. Filtrez par service, distance et disponibilité instantanée.</p>
        <ul class="space-y-3 mb-8">
          @foreach([['📍','Géolocalisation GPS précise'],['🔴','Disponibilité temps réel'],['📏','Distance et temps de trajet estimé'],['🔔','Notifications push instantanées']] as [$ic,$txt])
          <li class="flex items-center gap-3 text-sm" style="color:var(--sm-muted)"><span class="text-xl">{{ $ic }}</span>{{ $txt }}</li>
          @endforeach
        </ul>
        <a href="{{ url('/services') }}" class="btn-sm-primary">Explorer la carte</a>
      </div>
      <div class="reveal d2 rounded-2xl overflow-hidden shadow-2xl border h-80" style="border-color:var(--sm-border)"
           x-data="serveMap({ providers: [{lat:33.5731,lng:-7.5898,name:'Karim M.',service:'Plombier'},{lat:33.581,lng:-7.612,name:'Youssef A.',service:'Mécanicien'},{lat:33.562,lng:-7.579,name:'Sara B.',service:'Professeure'}] })">
        <div data-map class="w-full h-full"></div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     9. TÉMOIGNAGES
     ═══════════════════════════════════════════════════════ --}}
<section id="temoignages" class="py-16 lg:py-20 bg-white" aria-labelledby="testimonials-title">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="text-center mb-10 lg:mb-12 reveal">
      <span class="sm-tag">Témoignages</span>
      <h2 id="testimonials-title" class="sm-title">Ce que disent nos utilisateurs</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
      @php
      $testimonials = [
        ['Hamid Berrada','Client','J\'avais une fuite d\'eau en urgence à 22h. En 10 minutes j\'avais un plombier qui se déplaçait. Service incroyable !',5,'1F6E6C'],
        ['Nadia Chraibi','Cliente','Ma voiture est tombée en panne sur la route. ServeMe m\'a trouvé un mécanicien en moins de 20 minutes. Je recommande !',5,'7c3aed'],
        ['Omar Tazi','Prestataire Électricien','Depuis que j\'ai rejoint ServeMe, j\'ai doublé mon nombre de clients. Simple et les paiements sont toujours à l\'heure.',5,'d97706'],
      ];
      @endphp
      @foreach($testimonials as $i => [$name,$role,$text,$rating,$color])
      <div class="testi-card reveal d{{ $i+1 }}">
        <div class="flex gap-0.5 text-amber-400 mb-4">@for($s=0;$s<$rating;$s++)★@endfor</div>
        <p class="text-sm leading-relaxed mb-5 italic" style="color:var(--sm-muted)">"{{ $text }}"</p>
        <div class="flex items-center gap-3 pt-4" style="border-top:1px solid var(--sm-border)">
          <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background={{ $color }}&color=fff&size=80" class="w-10 h-10 rounded-full" alt="{{ $name }}">
          <div>
            <p class="font-bold text-sm" style="color:var(--sm-text)">{{ $name }}</p>
            <p class="text-xs" style="color:var(--sm-muted)">{{ $role }}</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     10. APP & NEWSLETTER
     ═══════════════════════════════════════════════════════ --}}
<section id="newsletter" class="py-16 lg:py-20 bg-[var(--sm-slate)]" aria-labelledby="app-title">
  <div class="container mx-auto px-6 lg:px-20">
    <div class="rounded-3xl px-6 lg:px-8 py-14 lg:py-16 text-center relative overflow-hidden reveal max-w-4xl mx-auto"
         style="background:linear-gradient(135deg, var(--sm-dark), var(--sm-primary))">
      <div class="absolute inset-0 opacity-10"
           style="background-image:url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4z\'/%3E%3C/g%3E%3C/svg%3E')"></div>
      <div class="relative z-10">
        <p class="text-sm font-semibold uppercase tracking-wider mb-3" style="color:var(--sm-accent)">Disponible sur mobile</p>
        <h2 id="app-title" class="text-3xl md:text-4xl font-extrabold text-white mb-4">ServeMe dans votre poche</h2>
        <p class="max-w-md mx-auto mb-10" style="color:rgba(255,255,255,.7)">Téléchargez l'app et réservez vos services partout, à tout moment. Notifications push, suivi GPS et paiement intégré.</p>
        <div class="flex flex-wrap gap-4 justify-center mb-10">
          <a href="#" class="inline-flex items-center gap-3 bg-white text-slate-900 font-bold px-6 py-3 rounded-2xl hover:bg-slate-100 transition-colors shadow-lg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
            <span><span class="text-xs block font-normal opacity-70">Télécharger sur</span>App Store</span>
          </a>
          <a href="#" class="inline-flex items-center gap-3 bg-white text-slate-900 font-bold px-6 py-3 rounded-2xl hover:bg-slate-100 transition-colors shadow-lg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3.18 23.76c.37.21.8.22 1.19.03l12.15-6.86-2.48-2.49-10.86 9.32zM.87 1.24C.34 1.62 0 2.24 0 3v18c0 .76.34 1.38.87 1.76l.09.07 10.08-10.08v-.22L.96 1.17l-.09.07zM20.23 10.5l-2.65-1.5-2.79 2.79 2.79 2.79 2.66-1.5c.76-.43.76-1.15 0-1.58zM4.37.21L16.52 7.07l-2.48 2.49L3.18.24C3.57.05 4 .06 4.37.21z"/></svg>
            <span><span class="text-xs block font-normal opacity-70">Télécharger sur</span>Google Play</span>
          </a>
        </div>
        <div class="max-w-sm mx-auto" x-data="{ email:'', sent:false }">
          <p class="text-sm mb-3" style="color:var(--sm-accent)">Restez informé des nouveautés</p>
          <div class="flex gap-2">
            <input type="email" x-model="email" placeholder="votre@email.com" class="nl-input">
            <button @click="sent=true" class="nl-btn">
              <span x-show="!sent">S'abonner</span>
              <span x-show="sent" x-cloak>✓ Ok !</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection