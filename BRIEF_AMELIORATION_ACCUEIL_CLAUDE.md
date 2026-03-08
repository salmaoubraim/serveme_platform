# Brief : Améliorer la page d'accueil ServeMe (pour Claude)

## Contexte projet

- **Application** : ServeMe — plateforme de réservation de services à domicile (mécanicien, plombier, électricien, cours particuliers, etc.).
- **Stack** : Laravel 12, Breeze, Livewire, Alpine.js, Vite, Tailwind CSS, SQLite.
- **Fichiers principaux** :
  - `resources/views/home.blade.php` — contenu de la page d'accueil
  - `resources/views/layouts/app.blade.php` — layout (header, footer)
  - `resources/css/app.css` — variables CSS et classes (teal, --serve-primary, --serve-accent, etc.)

## Contenu actuel de l'accueil (ordre des sections)

1. **Hero** : titre "Trouvez le bon prestataire, instantanément.", sous-titre, CTA "Trouver un service", cartes flottantes à droite (prestataire dispo, suivi intervention, avis).
2. **Stats** : 200+ prestataires, 1500+ réservations, 98% satisfaits, 15 min temps de réponse (compteurs animés).
3. **Bannière urgence** : "Intervention urgente ?" + lien vers services immédiats.
4. **Catégories** : grille de cartes (Mécanique, Plomberie, Électricité, Domicile, Éducation, Santé, Peinture, Jardinage, Entreprise) avec liens vers `/services?category=slug`.
5. **Comment ça marche** : 3 étapes Client + 3 étapes Prestataire (badges, connecteurs).
6. **Prestataires en vedette** : 4 cartes (avatar, note, distance, boutons Profil / Réserver).
7. **CTA double** : "Vous cherchez un service ?" (carte teal) et "Vous êtes prestataire ?" (carte slate).
8. **Carte** : bloc "Prestataires proches en temps réel" + carte Leaflet (providers factices).
9. **Témoignages** : 3 cartes (avis, note, nom, rôle).
10. **App / Newsletter** : bannière sombre "ServeMe dans votre poche", boutons App Store / Google Play, formulaire newsletter (Alpine `x-data`).

## Contraintes techniques

- **Ne pas casser** : `@extends('layouts.app')`, `@section('content')` / `@endsection`, routes Laravel (`url()`, `route()`), directives Blade (`@auth`, `@guest`, `@foreach`, etc.).
- **CSS** : utiliser les variables existantes (`var(--serve-primary)`, `var(--serve-accent)`, `var(--serve-bg-light)`, `var(--serve-text)`) et Tailwind ; ajouter des classes dans `app.css` si besoin.
- **JS** : Alpine.js pour interactions (dropdowns, compteurs, carte). Compteur déjà dans `app.js` : `Alpine.data('counter', ...)`. Carte : `serveMap` avec Leaflet.
- **Images** : pas d'assets locaux pour avatars ; `ui-avatars.com` utilisé. Tu peux proposer des placeholders ou chemins `asset('images/...')` si tu ajoutes des visuels.

## Pistes d'amélioration à proposer / implémenter

### Design et UX

- **Hero** : renforcer la hiérarchie visuelle (taille, contraste, espacement) ; améliorer le responsive des cartes flottantes (mobile : les masquer ou les remplacer par un bloc plus simple).
- **Sections** : alterner fonds (slate-50 / white) de façon plus lisible ; ajouter des séparateurs ou courbes SVG entre sections pour un rendu plus moderne.
- **Cartes** : ombres, rayons, états hover cohérents ; micro-interactions (scale, transition) sur les cartes catégories et prestataires.
- **Typo** : vérifier contraste (WCAG), tailles sur mobile ; garder Plus Jakarta Sans (déjà dans le layout).

### Contenu et conversion

- **CTA** : rendre les boutons principaux plus visibles (taille, couleur, libellés plus actionnels).
- **Preuve sociale** : renforcer témoignages (photo, nom, métier) ; éventuellement ajouter logos partenaires ou labels ("Paiement sécurisé", "Prestataires vérifiés").
- **Urgence** : la bannière rouge peut être plus percutante (icône, chiffre "< 15 min") sans être agressive.

### Performance et accessibilité

- **Images** : attributs `width`/`height` ou `aspect-ratio` pour éviter les sauts de layout ; `loading="lazy"` sur images hors viewport.
- **Contrastes** : texte sur hero (blanc sur teal) et sur bannières (blanc sur rouge/slate) conformes AA.
- **Focus** : styles `focus:ring` / `focus-visible` sur tous les liens et boutons.
- **Sémantique** : sections avec `aria-labelledby` si besoin ; un seul `h1` (déjà le cas dans le hero).

### Fonctionnel (sans backend)

- **Recherche hero** : actuellement un seul bouton "Trouver un service" vers `/services`. Tu peux proposer une mini barre (catégorie + adresse) qui redirige vers `/services?category=...&address=...` sans JavaScript complexe (form GET).
- **Newsletter** : le bouton "S'abonner" ne fait rien côté serveur. Proposer un `action` et `method` (ex. route `newsletter.subscribe`) à brancher plus tard, ou laisser un commentaire pour l'intégration.
- **Carte** : s'assurer que la carte Leaflet s'initialise bien (classe `data-map`, config dans `serveMap`). Proposer un fallback si pas de clé/JS (image statique ou message).

### Mobile

- **Navigation** : le menu hamburger existe déjà ; vérifier que les sections sont bien espacées et lisibles sur petit écran.
- **Touch** : zones cliquables suffisamment grandes (min 44px) pour les liens et boutons.
- **Hero** : sur mobile, masquer ou simplifier les cartes flottantes pour réduire le scroll et le bruit visuel.

## Livrable attendu

- Modifications concrètes dans `home.blade.php` (et si besoin dans `app.css` ou `app.js`) avec des explications courtes en commentaires.
- Si tu proposes des changements dans le layout (`app.blade.php`), limiter au strict nécessaire pour l'accueil.
- Conserver la structure en 10 sections (ou proposer un ordre réorganisé si tu le justifies).
- Ne pas supprimer les fonctionnalités existantes (liens vers `/services`, `/providers`, `/reservations/create`, `route('login')`, `route('register')`) sans les remplacer par un équivalent.

## Résumé en une phrase

Améliore la page d'accueil ServeMe (design, UX, mobile, accessibilité, conversion) en restant dans le cadre Laravel/Blade, Tailwind et Alpine.js, sans casser le layout ni les liens existants.
