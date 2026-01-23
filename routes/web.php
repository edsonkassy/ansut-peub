<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Bachelier\BachelierController;
use App\Http\Controllers\Bachelier\OpportuniteController as BachelierOpportuniteController;
use App\Http\Controllers\Bachelier\CandidatureController as BachelierCandidatureController;
use App\Http\Controllers\Bachelier\FavoriController;
use App\Http\Controllers\Bachelier\ProfileController as BachelierProfileController;
use App\Http\Controllers\Bachelier\ParcoursUniversitaireController;
use App\Http\Controllers\Partenaire\PartenaireController;
use App\Http\Controllers\Partenaire\OpportuniteController as PartenaireOpportuniteController;
use App\Http\Controllers\Partenaire\CandidatureController as PartenaireCandidatureController;
use App\Http\Controllers\Partenaire\AnalyticsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController as AdminAgentController;
use App\Http\Controllers\Admin\BachelierManagementController;
use App\Http\Controllers\Admin\PartenaireManagementController;
use App\Http\Controllers\Admin\OpportuniteManagementController;
use App\Http\Controllers\Admin\DotationController;
use App\Http\Controllers\Admin\DotationInventaireController;
use App\Http\Controllers\Admin\DotationFournisseurController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\Bachelier\AgentController as BachelierAgentController;
use App\Http\Controllers\Partenaire\AgentController as PartenaireAgentController;
use App\Http\Controllers\ImageGenerationController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Actualités dynamiques
Route::get('/actualites', [ArticleController::class, 'index'])->name('actualites');
Route::get('/actualites/recherche', [ArticleController::class, 'search'])->name('actualites.search');
Route::get('/actualites/categorie/{categorie}', [ArticleController::class, 'category'])->name('actualites.category');
Route::get('/actualites/tag/{tag}', [ArticleController::class, 'tag'])->name('actualites.tag');
Route::get('/actualites/populaires', [ArticleController::class, 'popular'])->name('actualites.popular');
Route::get('/actualites/rss', [ArticleController::class, 'rss'])->name('actualites.rss');
Route::get('/actualite/{article:slug}', [ArticleController::class, 'show'])->name('actualite');

// Actualité statique pour compatibilité (redirection)
Route::get('/actualites/lancement-peub-2024', function () {
    return redirect()->route('actualites');
})->name('actualite.lancement-peub-2024');

// FAQ
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Legal Pages
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/cookies', function () {
    return view('cookies');
})->name('cookies');

Route::get('/mentions-legales', function () {
    return view('mentions-legales');
})->name('mentions-legales');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Route publique pour devenir partenaire
Route::get('/devenir-partenaire', [PartenaireController::class, 'create'])->name('partenaire.register');
Route::post('/devenir-partenaire', [PartenaireController::class, 'store'])->name('partenaire.register.store');
Route::get('/devenir-partenaire/success', [PartenaireController::class, 'success'])->name('partenaire.register.success');

// Authentication Routes (OTP-based + Social Login)
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'sendOtp'])->name('send-otp');
    Route::get('/verify', [AuthController::class, 'showVerify'])->name('verify');
    Route::post('/verify', [AuthController::class, 'verifyOtp'])->name('verify-otp');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Social Authentication Routes
    Route::get('/{provider}/redirect', [\App\Http\Controllers\Auth\SocialAuthController::class, 'redirect'])
         ->name('social.redirect')
         ->where('provider', 'google|facebook|microsoft|linkedin');
    Route::get('/{provider}/callback', [\App\Http\Controllers\Auth\SocialAuthController::class, 'callback'])
         ->name('social.callback')
         ->where('provider', 'google|facebook|microsoft|linkedin');
    
    // Complete Profile after Social Login
    Route::get('/complete-profile', [\App\Http\Controllers\Auth\SocialAuthController::class, 'showCompleteProfile'])
         ->name('complete-profile')
         ->middleware('auth');
    Route::post('/complete-profile/preview', [\App\Http\Controllers\Auth\SocialAuthController::class, 'showPreview'])
         ->name('complete-profile.preview')
         ->middleware('auth');
    Route::post('/complete-profile', [\App\Http\Controllers\Auth\SocialAuthController::class, 'completeProfile'])
         ->name('complete-profile.store')
         ->middleware('auth');
});

// Page d'attente de validation (accessible même si user est 'pending')
Route::get('/auth/pending-validation', function () {
    $user = Auth::user();
    
    // Si l'utilisateur est déjà actif, rediriger vers dashboard
    if ($user && $user->status === 'active') {
        return redirect()->route('dashboard');
    }
    
    // Si l'utilisateur n'a pas de profil bachelier, rediriger pour compléter
    if ($user && $user->role === 'bachelier' && !$user->bachelier) {
        return redirect()->route('auth.complete-profile');
    }
    
    return view('auth.pending-validation');
})->middleware('auth')->name('auth.pending-validation');

// Route pour la page de restriction mobile admin
Route::get('/admin/mobile-restricted', function () {
    return view('admin.mobile-restricted');
})->name('admin.mobile-restricted');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Redirect
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Bachelier Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:bachelier', 'active', 'bachelier.complete'])->prefix('bachelier')->name('bachelier.')->group(function () {
        Route::get('/dashboard', [BachelierController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [BachelierProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [BachelierProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/status', [BachelierProfileController::class, 'updateStatus'])->name('profile.status');
        Route::get('/profile/download/{type}', [BachelierProfileController::class, 'downloadDocument'])->name('profile.download');
        Route::get('/profile/export', [BachelierProfileController::class, 'exportData'])->name('profile.export');
        Route::delete('/profile/documents/{type}', [BachelierProfileController::class, 'destroyDocument'])->name('documents.destroy');
        
        // Parcours Universitaires
        Route::resource('parcours', ParcoursUniversitaireController::class)->except(['show']);
        
        // Dotations (pour les boursiers PEUB)
        Route::get('/dotations', [BachelierController::class, 'dotations'])->name('dotations');
        
        // Opportunités
        Route::get('/opportunites', [BachelierOpportuniteController::class, 'index'])->name('opportunites');
        Route::get('/opportunites/{opportunite}', [BachelierOpportuniteController::class, 'show'])->name('opportunites.show');
        
        // Candidatures
        Route::get('/candidatures', [BachelierCandidatureController::class, 'index'])->name('candidatures');
        Route::post('/candidatures', [BachelierCandidatureController::class, 'store'])->name('candidatures.store');
        Route::get('/candidatures/{candidature}', [BachelierCandidatureController::class, 'show'])->name('candidatures.show');
        Route::delete('/candidatures/{candidature}', [BachelierCandidatureController::class, 'withdraw'])->name('candidatures.destroy');
        
        // Compatibilité IA
        Route::post('/compatibility/{opportunite}', [\App\Http\Controllers\Bachelier\CompatibilityController::class, 'calculateScore'])->name('compatibility.calculate');
        
        // Favoris
        Route::get('/favoris', [FavoriController::class, 'index'])->name('favoris');
        Route::post('/favoris', [FavoriController::class, 'store'])->name('favoris.store');
        Route::delete('/favoris', [FavoriController::class, 'destroy'])->name('favoris.destroy');
        Route::post('/favoris/toggle', [FavoriController::class, 'toggle'])->name('favoris.toggle');
        Route::delete('/favoris/bulk', [FavoriController::class, 'bulkDestroy'])->name('favoris.bulk-destroy');
        
        // Agent IA
        Route::post('/agent/chat', [BachelierAgentController::class, 'chat'])->name('agent.chat');
        
        // Bibliothèque
        Route::prefix('library')->name('library.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Bachelier\LibraryController::class, 'index'])->name('index');
            Route::get('/favorites', [\App\Http\Controllers\Bachelier\LibraryController::class, 'favorites'])->name('favorites');
            Route::get('/{resource}', [\App\Http\Controllers\Bachelier\LibraryController::class, 'show'])->name('show');
            Route::get('/{resource}/download', [\App\Http\Controllers\Bachelier\LibraryController::class, 'download'])->name('download');
            Route::post('/{resource}/favorite', [\App\Http\Controllers\Bachelier\LibraryController::class, 'toggleFavorite'])->name('favorite.toggle');
            Route::post('/{resource}/like', [\App\Http\Controllers\Bachelier\LibraryController::class, 'toggleLike'])->name('like.toggle');
            Route::post('/{resource}/comments', [\App\Http\Controllers\Bachelier\LibraryController::class, 'storeComment'])->name('comments.store');
            Route::post('/comments/{comment}/like', [\App\Http\Controllers\Bachelier\LibraryController::class, 'toggleCommentLike'])->name('comments.like');
        });

        // Forum
        Route::prefix('forum')->name('forum.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Bachelier\ForumController::class, 'index'])->name('index');
            Route::get('/search', [\App\Http\Controllers\Bachelier\ForumController::class, 'search'])->name('search');
            Route::get('/favorites', [\App\Http\Controllers\Bachelier\ForumController::class, 'favorites'])->name('favorites');
            Route::get('/members', [\App\Http\Controllers\Bachelier\ForumController::class, 'members'])->name('members');
            
            // Création de discussions (page supprimée) – création via modal uniquement
            Route::post('/store-thread', [\App\Http\Controllers\Bachelier\ForumController::class, 'storeThread'])->name('store-thread');
            
            // Catégories et discussions
            Route::get('/category/{category}', [\App\Http\Controllers\Bachelier\ForumController::class, 'category'])->name('category');
            Route::get('/thread/{thread}', [\App\Http\Controllers\Bachelier\ForumController::class, 'thread'])->name('thread');
            
            // Messages
            Route::post('/thread/{thread}/posts', [\App\Http\Controllers\Bachelier\ForumController::class, 'storePost'])->name('store-post');
            Route::get('/posts/{post}/edit', [\App\Http\Controllers\Bachelier\ForumController::class, 'editPost'])->name('edit-post');
            Route::put('/posts/{post}', [\App\Http\Controllers\Bachelier\ForumController::class, 'updatePost'])->name('update-post');
            Route::delete('/posts/{post}', [\App\Http\Controllers\Bachelier\ForumController::class, 'deletePost'])->name('delete-post');
            
            // Favoris et réactions
            Route::post('/{thread}/favorite', [\App\Http\Controllers\Bachelier\ForumController::class, 'toggleFavorite'])->name('toggle-favorite');
            Route::post('/reactions/toggle', [\App\Http\Controllers\Bachelier\ForumController::class, 'toggleReaction'])->name('toggle-reaction');
        });

        // Inbox - Messages privés entre bacheliers
        Route::prefix('inbox')->name('inbox.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Bachelier\InboxController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Bachelier\InboxController::class, 'create'])->name('create');
            Route::post('/start-conversation', [\App\Http\Controllers\Bachelier\InboxController::class, 'startConversation'])->name('start-conversation');
            Route::get('/search-bacheliers', [\App\Http\Controllers\Bachelier\InboxController::class, 'searchBacheliers'])->name('search-bacheliers');
            // Endpoint alternatif de recherche utilisé côté front
            Route::get('/search-users', [\App\Http\Controllers\Bachelier\InboxController::class, 'searchUsers'])->name('search-users');
            Route::get('/{conversation}', [\App\Http\Controllers\Bachelier\InboxController::class, 'show'])->name('show');
            Route::post('/{conversation}', [\App\Http\Controllers\Bachelier\InboxController::class, 'store'])->name('store');
            Route::post('/{conversation}/archive', [\App\Http\Controllers\Bachelier\InboxController::class, 'archive'])->name('archive');
            // API messages pour chargement et réponse
            Route::get('/{conversation}/messages', [\App\Http\Controllers\Bachelier\InboxController::class, 'getMessages'])->name('messages');
            Route::post('/{conversation}/reply', [\App\Http\Controllers\Bachelier\InboxController::class, 'reply'])->name('reply');
            // Suppression d'un message
            Route::delete('/messages/{message}', [\App\Http\Controllers\Bachelier\InboxController::class, 'destroyMessage'])->name('messages.destroy');
            // Suppression d'une conversation entière
            Route::delete('/{conversation}', [\App\Http\Controllers\Bachelier\InboxController::class, 'destroyConversation'])->name('destroy');
            // Fallback POST pour suppression (compat navigateurs/proxies)
            Route::post('/{conversation}/destroy', [\App\Http\Controllers\Bachelier\InboxController::class, 'destroyConversation'])->name('destroy.post');
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Partenaire Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:partenaire'])->prefix('partenaire')->name('partenaire.')->group(function () {
        Route::get('/profile', [PartenaireController::class, 'profile'])->name('profile');
        Route::put('/profile', [PartenaireController::class, 'updateProfile'])->name('profile.update');
    });

    Route::middleware(['role:partenaire', 'verified_partner'])->prefix('partenaire')->name('partenaire.')->group(function () {
        Route::get('/dashboard', [PartenaireController::class, 'dashboard'])->name('dashboard');
        
        // Opportunités
        Route::resource('opportunites', PartenaireOpportuniteController::class);
        Route::post('opportunites/generate-image', [PartenaireOpportuniteController::class, 'generateImage'])->name('opportunites.generate-image');
        
        // Candidatures reçues
        Route::get('/candidatures', [PartenaireCandidatureController::class, 'index'])->name('candidatures.index');
        Route::get('/candidatures/{candidature}', [PartenaireCandidatureController::class, 'show'])->name('candidatures.show');
        Route::put('/candidatures/{candidature}', [PartenaireCandidatureController::class, 'update'])->name('candidatures.update');
        
        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
        
        // Conversations
        Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations');
        Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
        
        // Agent IA
        Route::post('/agent/chat', [PartenaireAgentController::class, 'chat'])->name('agent.chat');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin', 'admin.no_mobile'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Gestion des Bacheliers
        Route::prefix('bacheliers')->name('bacheliers.')->group(function () {
            Route::get('/', [BachelierManagementController::class, 'index'])->name('index');
            Route::get('/bareme', [BachelierManagementController::class, 'bareme'])->name('bareme');
            Route::get('/by-year/{year}', [BachelierManagementController::class, 'byYear'])->name('by-year');
            Route::get('/export', [BachelierManagementController::class, 'export'])->name('export');
            Route::get('/export-excel', [BachelierManagementController::class, 'exportExcel'])->name('export-excel');
            Route::get('/analytics', [BachelierManagementController::class, 'analytics'])->name('analytics');
            Route::post('/bulk-validate', [BachelierManagementController::class, 'bulkValidate'])->name('bulk-validate');
            Route::post('/validate-multiple', [BachelierManagementController::class, 'validateMultiple'])->name('validate-multiple');
            Route::get('/{bachelier}', [BachelierManagementController::class, 'show'])->name('show');
            Route::get('/{bachelier}/edit', [BachelierManagementController::class, 'edit'])->name('edit');
            Route::put('/{bachelier}', [BachelierManagementController::class, 'update'])->name('update');
            Route::delete('/{bachelier}', [BachelierManagementController::class, 'destroy'])->name('destroy');
            Route::patch('/{bachelier}/toggle-boursier', [BachelierManagementController::class, 'toggleBoursier'])->name('toggle-boursier');
            
            // Attribution de dotation
            Route::post('/{bachelier}/dotations', [BachelierManagementController::class, 'storeDotation'])->name('dotations.store');

            // Validation des bacheliers
            Route::post('/{user}/validate', [BachelierManagementController::class, 'validateBachelier'])->name('validate');
            Route::post('/{user}/suspend', [BachelierManagementController::class, 'suspendBachelier'])->name('suspend');
        });
        
        // Gestion des Partenaires
        Route::prefix('partenaires')->name('partenaires.')->group(function () {
            Route::get('/', [PartenaireManagementController::class, 'index'])->name('index');
            Route::get('/{partenaire}', [PartenaireManagementController::class, 'show'])->name('show');
            Route::put('/{partenaire}/verify', [PartenaireManagementController::class, 'verify'])->name('verify');
            Route::put('/{partenaire}/reject', [PartenaireManagementController::class, 'reject'])->name('reject');
            Route::patch('/{partenaire}/toggle-status', [PartenaireManagementController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Gestion des Administrateurs
        Route::prefix('administrators')->name('administrators.')->group(function () {
            Route::middleware(['admin.permission:users.administrators.create'])->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\AdminManagementController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\AdminManagementController::class, 'store'])->name('store');
            });

            Route::middleware(['admin.permission:users.administrators.view'])->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\AdminManagementController::class, 'index'])->name('index');
                Route::get('/{administrator}', [\App\Http\Controllers\Admin\AdminManagementController::class, 'show'])->name('show');
            });
            
            Route::middleware(['admin.permission:users.administrators.edit'])->group(function () {
                Route::get('/{administrator}/edit', [\App\Http\Controllers\Admin\AdminManagementController::class, 'edit'])->name('edit');
                Route::put('/{administrator}', [\App\Http\Controllers\Admin\AdminManagementController::class, 'update'])->name('update');
            });
            
            Route::middleware(['admin.permission:users.administrators.delete'])->group(function () {
                Route::delete('/{administrator}', [\App\Http\Controllers\Admin\AdminManagementController::class, 'destroy'])->name('destroy');
            });
            
            Route::middleware(['admin.permission:users.administrators.roles'])->group(function () {
                Route::get('/roles/manage', [\App\Http\Controllers\Admin\AdminManagementController::class, 'manageRoles'])->name('roles');
                Route::post('/roles', [\App\Http\Controllers\Admin\AdminManagementController::class, 'storeRole'])->name('roles.store');
                Route::put('/roles/{role}', [\App\Http\Controllers\Admin\AdminManagementController::class, 'updateRole'])->name('roles.update');
                Route::delete('/roles/{role}', [\App\Http\Controllers\Admin\AdminManagementController::class, 'destroyRole'])->name('roles.destroy');
            });
        });
        
        // Visualisation des Boursiers
        Route::get('/boursiers-map', [\App\Http\Controllers\Admin\BoursierController::class, 'index'])->name('boursiers.map');
        
        // Gestion des Opportunités
        Route::prefix('opportunites')->name('opportunites.')->group(function () {
            Route::get('/', [OpportuniteManagementController::class, 'index'])->name('index');
            Route::get('/{opportunite}', [OpportuniteManagementController::class, 'show'])->name('show');
            Route::put('/{opportunite}/moderate', [OpportuniteManagementController::class, 'moderate'])->name('moderate');
        });
        
        // Gestion des Candidatures
        Route::prefix('candidatures')->name('candidatures.')->group(function () {
            Route::middleware(['admin.permission:candidatures.view'])->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\CandidatureController::class, 'index'])->name('index');
                Route::get('/{candidature}', [\App\Http\Controllers\Admin\CandidatureController::class, 'show'])->name('show');
            });
        });
        
        // --- Gestion des Dotations ---
        // IMPORTANT: Les routes spécifiques (inventaire) doivent être déclarées avant la route ressource générique pour éviter les conflits.
        
        // Gestion de l'inventaire des dotations
        Route::prefix('dotations/inventaire')->name('dotations.inventaire.')->group(function () {
            Route::middleware(['admin.permission:inventaire.view'])->group(function () {
                Route::get('/', [DotationInventaireController::class, 'index'])->name('index');
            });
            Route::middleware(['admin.permission:inventaire.manage'])->group(function () {
                Route::get('/create', [DotationInventaireController::class, 'create'])->name('create');
                Route::post('/', [DotationInventaireController::class, 'store'])->name('store');
            });
            Route::middleware(['admin.permission:inventaire.view'])->group(function () {
                Route::get('/{inventaire}', [DotationInventaireController::class, 'show'])->name('show');
            });
            Route::middleware(['admin.permission:inventaire.manage'])->group(function () {
                Route::get('/{inventaire}/edit', [DotationInventaireController::class, 'edit'])->name('edit');
                Route::put('/{inventaire}', [DotationInventaireController::class, 'update'])->name('update');
                Route::delete('/{inventaire}', [DotationInventaireController::class, 'destroy'])->name('destroy');
            });
        });
        
        // Gestion Fournisseurs
        Route::prefix('dotations/fournisseurs')->name('dotations.fournisseurs.')->group(function () {
            Route::middleware(['admin.permission:fournisseurs.view'])->group(function () {
                Route::get('/', [DotationFournisseurController::class, 'index'])->name('index');
            });
            Route::middleware(['admin.permission:fournisseurs.manage'])->group(function () {
                Route::get('/create', [DotationFournisseurController::class, 'create'])->name('create');
                Route::post('/', [DotationFournisseurController::class, 'store'])->name('store');
            });
            Route::middleware(['admin.permission:fournisseurs.view'])->group(function () {
                Route::get('/{fournisseur}', [DotationFournisseurController::class, 'show'])->name('show');
            });
            Route::middleware(['admin.permission:fournisseurs.manage'])->group(function () {
                Route::get('/{fournisseur}/edit', [DotationFournisseurController::class, 'edit'])->name('edit');
                Route::put('/{fournisseur}', [DotationFournisseurController::class, 'update'])->name('update');
                Route::delete('/{fournisseur}', [DotationFournisseurController::class, 'destroy'])->name('destroy');
            });
        });

        // Gestion des Attributions de dotations
        Route::prefix('dotations')->name('dotations.')->group(function () {
            Route::middleware(['admin.permission:dotations.view'])->group(function () {
                Route::get('/', [DotationController::class, 'index'])->name('index');
                Route::get('/{dotation}', [DotationController::class, 'show'])->name('show')->where('dotation', '[0-9]+');
            });
            Route::middleware(['admin.permission:dotations.edit'])->group(function () {
                Route::get('/{dotation}/edit', [DotationController::class, 'edit'])->name('edit')->where('dotation', '[0-9]+');
                Route::put('/{dotation}', [DotationController::class, 'update'])->name('update')->where('dotation', '[0-9]+');
            });
            Route::middleware(['admin.permission:dotations.delete'])->group(function () {
                Route::delete('/{dotation}', [DotationController::class, 'destroy'])->name('destroy')->where('dotation', '[0-9]+');
            });
        });
        
        // Analytics Avancées
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/exports', [AdminController::class, 'exports'])->name('exports');
        
        // Agent IA harmonisé
        Route::post('/agent/chat', [AdminAgentController::class, 'chat'])->name('agent.chat');
        
        // Gestion de la Bibliothèque
        Route::prefix('library')->name('library.')->group(function () {
            // Catégories
            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\LibraryCategoryController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\Admin\LibraryCategoryController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\LibraryCategoryController::class, 'store'])->name('store');
                Route::get('/{category}/edit', [\App\Http\Controllers\Admin\LibraryCategoryController::class, 'edit'])->name('edit');
                Route::put('/{category}', [\App\Http\Controllers\Admin\LibraryCategoryController::class, 'update'])->name('update');
                Route::delete('/{category}', [\App\Http\Controllers\Admin\LibraryCategoryController::class, 'destroy'])->name('destroy');
            });

            // Ressources
            Route::prefix('resources')->name('resources.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\LibraryResourceController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\Admin\LibraryResourceController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\LibraryResourceController::class, 'store'])->name('store');
                Route::get('/{resource}', [\App\Http\Controllers\Admin\LibraryResourceController::class, 'show'])->name('show');
                Route::get('/{resource}/edit', [\App\Http\Controllers\Admin\LibraryResourceController::class, 'edit'])->name('edit');
                Route::put('/{resource}', [\App\Http\Controllers\Admin\LibraryResourceController::class, 'update'])->name('update');
                Route::delete('/{resource}', [\App\Http\Controllers\Admin\LibraryResourceController::class, 'destroy'])->name('destroy');
            });

            // Commentaires
            Route::patch('/comments/{comment}/toggle', [\App\Http\Controllers\Admin\LibraryResourceController::class, 'toggleComment'])->name('comments.toggle');
            Route::delete('/comments/{comment}', [\App\Http\Controllers\Admin\LibraryResourceController::class, 'deleteComment'])->name('comments.destroy');
        });
        
        // Gestion des articles
        Route::prefix('articles')->name('articles.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ArticleController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\ArticleController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\ArticleController::class, 'store'])->name('store');
            Route::get('/{article}', [\App\Http\Controllers\Admin\ArticleController::class, 'show'])->name('show');
            Route::get('/{article}/edit', [\App\Http\Controllers\Admin\ArticleController::class, 'edit'])->name('edit');
            Route::put('/{article}', [\App\Http\Controllers\Admin\ArticleController::class, 'update'])->name('update');
            Route::delete('/{article}', [\App\Http\Controllers\Admin\ArticleController::class, 'destroy'])->name('destroy');
            
            // Actions spéciales
            Route::patch('/{article}/publish', [\App\Http\Controllers\Admin\ArticleController::class, 'publish'])->name('publish');
            Route::patch('/{article}/unpublish', [\App\Http\Controllers\Admin\ArticleController::class, 'unpublish'])->name('unpublish');
            Route::patch('/{article}/toggle-featured', [\App\Http\Controllers\Admin\ArticleController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/{article}/duplicate', [\App\Http\Controllers\Admin\ArticleController::class, 'duplicate'])->name('duplicate');
            
            // Analytics
            Route::get('/analytics/index', [\App\Http\Controllers\Admin\ArticleController::class, 'analytics'])->name('analytics');
        });

        // Gestion des partenaires
        Route::get('/partenaires', [PartenaireManagementController::class, 'index'])->name('partenaires.index');
        Route::get('/partenaires/{partenaire}', [PartenaireManagementController::class, 'show'])->name('partenaires.show');
        Route::get('/partenaires/{partenaire}/edit', [PartenaireManagementController::class, 'edit'])->name('partenaires.edit');
        Route::patch('/partenaires/{partenaire}', [PartenaireManagementController::class, 'update'])->name('partenaires.update');
        Route::patch('/partenaires/{partenaire}/toggle-status', [PartenaireManagementController::class, 'toggleStatus'])->name('partenaires.toggle-status');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Common Routes (All Authenticated Users)
    |--------------------------------------------------------------------------
    */
    
    // Conversations (Messages)
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::post('/conversations/{conversation}/messages', [ConversationController::class, 'storeMessage'])->name('conversations.messages.store');
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'unread'])->name('unread');
        Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });
    
    // IA Common - NOTE: IAController does not exist, commenting out to prevent errors.
    // Route::post('/ia/interaction', [IAController::class, 'storeInteraction'])->name('ia.interaction');
    // Route::post('/ia/feedback', [IAController::class, 'storeFeedback'])->name('ia.feedback');
});

/*
|--------------------------------------------------------------------------
| API Routes (for AJAX calls)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/notifications', function () {
        return response()->json(['notifications' => []]);
    })->name('api.notifications');
    
    Route::post('/mark-notification-read/{id}', function ($id) {
        return response()->json(['success' => true]);
    })->name('api.notifications.mark-read');
});

// Routes pour la génération d'images
Route::post('/generate-opportunity-image', [ImageGenerationController::class, 'generateOpportunityImage'])
    ->name('generate.opportunity.image')
    ->middleware(['auth', 'verified']);
