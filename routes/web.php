<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ServeMe — Web Routes
| Stack: Laravel 11 · Livewire 3 · Alpine.js · Tailwind CSS
|--------------------------------------------------------------------------
*/

// ══════════════════════════════════════════════════════════
//  PUBLIC ROUTES
// ══════════════════════════════════════════════════════════

Route::get('/', fn () => view('home'))->name('home');

Route::get('/about',   fn () => view('about'))->name('about');
Route::get('/blog',    fn () => view('blog'))->name('blog');
Route::get('/contact', fn () => view('contact'))->name('contact');
Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $request->validate(['name' => 'required|string|max:100', 'email' => 'required|email', 'message' => 'required|string|max:2000']);
    // TODO: envoyer email ou enregistrer en base
    return redirect()->route('contact')->with('success', 'Votre message a bien été envoyé. Nous vous répondrons rapidement.');
})->name('contact.send');

// Services & Prestataires (public)
Route::get('/services', [\App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{category}', fn ($category) => view('services.category', compact('category')))->name('services.category');
Route::get('/providers/{id}', [\App\Http\Controllers\ProviderController::class, 'show'])->name('providers.show');

// ══════════════════════════════════════════════════════════
//  AUTH ROUTES (Breeze / Jetstream ou manuel)
// ══════════════════════════════════════════════════════════

Route::middleware('guest')->group(function () {
    Route::get('/login',    fn () => view('auth.login'))->name('login');
    Route::get('/register', fn () => view('auth.register'))->name('register');
    Route::post('/login',    [\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// ══════════════════════════════════════════════════════════
//  CLIENT ROUTES  (role: client) — Interface client (layout client, pas dashboard admin)
// ══════════════════════════════════════════════════════════

Route::middleware(['auth'])->group(function () {

    // Dashboard commun : admin/prestataire → leur dashboard ; client → tableau de bord intégré (layout app, design vert)
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin'       => redirect()->route('admin.dashboard'),
            'prestataire' => redirect()->route('provider.dashboard'),
            default       => view('client.dashboard'),
        };
    })->name('dashboard');

    // Profil
    Route::get('/profile',       fn () => view('profile.edit'))->name('profile.edit');
    Route::patch('/profile',     [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',    [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Réservations (Client)
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/',           [\App\Http\Controllers\ReservationController::class, 'index'])->name('index');
        Route::get('/create',     fn () => view('reservations.create'))->name('create');
        Route::post('/',          [\App\Http\Controllers\ReservationController::class, 'store'])->name('store');
        Route::get('/{id}',       [\App\Http\Controllers\ReservationController::class, 'show'])->name('show');
        Route::patch('/{id}/cancel', [\App\Http\Controllers\ReservationController::class, 'cancel'])->name('cancel');
    });

    // Évaluation
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Notifications API
    Route::get('/api/notifications',          [\App\Http\Controllers\NotificationController::class, 'index']);
    Route::post('/api/notifications/read-all',[\App\Http\Controllers\NotificationController::class, 'readAll']);
});

// ══════════════════════════════════════════════════════════
//  PROVIDER ROUTES  (role: provider)
// ══════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:prestataire'])->prefix('provider')->name('provider.')->group(function () {

    Route::get('/dashboard',  fn () => view('provider.dashboard'))->name('dashboard');
    Route::get('/profile',    fn () => view('provider.profile'))->name('profile');

    // Disponibilité (toggled via Alpine.js → axios)
    Route::patch('/availability', [\App\Http\Controllers\Provider\AvailabilityController::class, 'update'])->name('availability');

    // Demandes reçues
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/',            fn () => view('provider.requests.index'))->name('index');
        Route::patch('/{id}/accept', [\App\Http\Controllers\Provider\RequestController::class, 'accept'])->name('accept');
        Route::patch('/{id}/refuse', [\App\Http\Controllers\Provider\RequestController::class, 'refuse'])->name('refuse');
        Route::patch('/{id}/propose',[\App\Http\Controllers\Provider\RequestController::class, 'propose'])->name('propose');
    });

    // Interventions
    Route::prefix('interventions')->name('interventions.')->group(function () {
        Route::get('/',          fn () => view('provider.interventions.index'))->name('index');
        Route::patch('/{id}/status', [\App\Http\Controllers\Provider\InterventionController::class, 'updateStatus'])->name('status');
    });

    // Paiements
    Route::get('/payments', fn () => view('provider.payments'))->name('payments');

    // Planning
    Route::get('/planning', fn () => view('provider.planning'))->name('planning');
});

// ══════════════════════════════════════════════════════════
//  ADMIN ROUTES  (role: admin)
// ══════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    // Gestion des comptes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',              fn () => view('admin.users.index'))->name('index');
        Route::patch('/{id}/approve',[\App\Http\Controllers\Admin\UserController::class, 'approve'])->name('approve');
        Route::patch('/{id}/suspend',[\App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('suspend');
        Route::delete('/{id}',       [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
    });

    // Catégories de services
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/',         fn () => view('admin.categories.index'))->name('index');
        Route::post('/',        [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('store');
        Route::put('/{id}',     [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}',  [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('destroy');
    });

    // Statistiques & rapports
    Route::get('/stats',        fn () => view('admin.stats'))->name('stats');
    Route::get('/transactions',  fn () => view('admin.transactions'))->name('transactions');

    // Interventions (vue globale)
    Route::get('/interventions', fn () => view('admin.interventions'))->name('interventions');
});