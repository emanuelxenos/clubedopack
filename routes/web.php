<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PurchaseController;

// ── Public Routes ──
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Auth Routes ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Authenticated Routes (Any Role) ──
Route::middleware('auth')->group(function () {
    // Purchases & Subscriptions
    Route::post('/pack/{pack}/purchase', [PurchaseController::class, 'purchasePack'])->name('pack.purchase');
    Route::post('/creator/{creator}/subscribe', [PurchaseController::class, 'subscribe'])->name('creator.subscribe');

    // My Library (Customer)
    Route::get('/my-library', [PurchaseController::class, 'library'])->name('library');
});

// ── Creator Dashboard Routes ──
Route::middleware(['auth', 'role:creator'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/packs', [DashboardController::class, 'packs'])->name('dashboard.packs');
    Route::get('/packs/create', [DashboardController::class, 'createPack'])->name('dashboard.packs.create');
    Route::post('/packs', [DashboardController::class, 'storePack'])->name('dashboard.packs.store');
    Route::get('/packs/{pack}/edit', [DashboardController::class, 'editPack'])->name('dashboard.packs.edit');
    Route::put('/packs/{pack}', [DashboardController::class, 'updatePack'])->name('dashboard.packs.update');
    Route::delete('/packs/{pack}', [DashboardController::class, 'destroyPack'])->name('dashboard.packs.destroy');
    Route::delete('/media/{media}', [DashboardController::class, 'deleteMedia'])->name('dashboard.media.destroy');
    Route::get('/earnings', [DashboardController::class, 'earnings'])->name('dashboard.earnings');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
});

// ── Admin Routes ──
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('admin.users.toggle');
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('admin.categories.destroy');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
});

// ── Pack Detail (must be before profile to avoid conflicts) ──
Route::get('/pack/{slug}', [PackController::class, 'show'])->name('pack.show');

// ── Secure Media Streaming ──
Route::get('/media/{media}/stream', [\App\Http\Controllers\MediaController::class, 'stream'])->middleware('auth')->name('media.stream');

// ── Static / Footer Pages ──
Route::get('/como-funciona', [\App\Http\Controllers\PageController::class, 'howItWorks'])->name('pages.how-it-works');
Route::get('/precos', [\App\Http\Controllers\PageController::class, 'pricing'])->name('pages.pricing');
Route::get('/ajuda', [\App\Http\Controllers\PageController::class, 'helpCenter'])->name('pages.help-center');
Route::get('/contato', [\App\Http\Controllers\PageController::class, 'contact'])->name('pages.contact');
Route::post('/contato', [\App\Http\Controllers\PageController::class, 'sendContact']);
Route::get('/faq', [\App\Http\Controllers\PageController::class, 'faq'])->name('pages.faq');
Route::get('/termos', [\App\Http\Controllers\PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacidade', [\App\Http\Controllers\PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/cookies', [\App\Http\Controllers\PageController::class, 'cookies'])->name('pages.cookies');

// ── Creator Profile (catch-all, must be LAST) ──
Route::get('/{username}', [ProfileController::class, 'show'])->name('profile.show');
