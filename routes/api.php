<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BachelierController;
use App\Http\Controllers\Api\OpportuniteController;
use App\Http\Controllers\Api\CandidatureController;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\ForumController;
use App\Http\Controllers\Api\InboxController;

/*
|--------------------------------------------------------------------------
| API Routes - PEUB Platform
|--------------------------------------------------------------------------
|
| API REST complète pour la plateforme PEUB
| - Authentification avec OTP
| - Espace Bachelier complet
| - Opportunités et Candidatures
| - Bibliothèque de ressources
| - Forum communautaire
| - Messagerie privée
|
*/

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/

// Authentification (Rate Limited: 5 tentatives par minute)
Route::prefix('auth')->middleware('throttle:5,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
});

// Opportunités publiques (lecture seule)
Route::get('opportunites', [OpportuniteController::class, 'index']);
Route::get('opportunites/{id}', [OpportuniteController::class, 'show']);
Route::get('opportunites/stats', [OpportuniteController::class, 'stats']);

/*
|--------------------------------------------------------------------------
| Routes Protégées (Authentification requise)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {
    
    // Authentification - Routes protégées
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Bachelier
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['role:bachelier'])->prefix('bachelier')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [BachelierController::class, 'dashboard']);
        
        // Profil
        Route::get('/profile', [BachelierController::class, 'profile']);
        Route::put('/profile', [BachelierController::class, 'updateProfile']);
        Route::post('/profile/photo', [BachelierController::class, 'uploadPhoto']);
        Route::post('/profile/cv', [BachelierController::class, 'uploadCV']);
        Route::get('/profile/export', [BachelierController::class, 'exportData']);
        
        // Dotations (pour les boursiers PEUB)
        Route::get('/dotations', [BachelierController::class, 'dotations']);
        
        // Opportunités
        Route::get('/opportunites', [OpportuniteController::class, 'index']);
        Route::get('/opportunites/recommended', [OpportuniteController::class, 'recommended']);
        Route::get('/opportunites/{id}', [OpportuniteController::class, 'show']);
        
        // Candidatures
        Route::get('/candidatures', [CandidatureController::class, 'index']);
        Route::post('/candidatures', [CandidatureController::class, 'store']);
        Route::get('/candidatures/stats', [CandidatureController::class, 'stats']);
        Route::get('/candidatures/{id}', [CandidatureController::class, 'show']);
        Route::put('/candidatures/{id}', [CandidatureController::class, 'update']);
        Route::delete('/candidatures/{id}', [CandidatureController::class, 'destroy']);
        
        // Bibliothèque
        Route::prefix('library')->group(function () {
            Route::get('/', [LibraryController::class, 'index']);
            Route::get('/categories', [LibraryController::class, 'categories']);
            Route::get('/favorites', [LibraryController::class, 'favorites']);
            Route::get('/{id}', [LibraryController::class, 'show']);
            Route::get('/{id}/download', [LibraryController::class, 'download']);
            Route::post('/{id}/favorite', [LibraryController::class, 'toggleFavorite']);
            Route::post('/{id}/like', [LibraryController::class, 'toggleLike']);
            Route::post('/{id}/comments', [LibraryController::class, 'storeComment']);
        });
        
        // Forum
        Route::prefix('forum')->group(function () {
            Route::get('/categories', [ForumController::class, 'categories']);
            Route::get('/threads', [ForumController::class, 'threads']);
            Route::post('/threads', [ForumController::class, 'storeThread']);
            Route::get('/threads/{id}', [ForumController::class, 'show']);
            Route::get('/favorites', [ForumController::class, 'favorites']);
            Route::post('/threads/{id}/favorite', [ForumController::class, 'toggleFavorite']);
            Route::post('/threads/{threadId}/posts', [ForumController::class, 'storePost']);
            Route::put('/posts/{postId}', [ForumController::class, 'updatePost']);
            Route::delete('/posts/{postId}', [ForumController::class, 'deletePost']);
            Route::post('/reactions/toggle', [ForumController::class, 'toggleReaction']);
        });
        
        // Messagerie (Inbox)
        Route::prefix('inbox')->group(function () {
            Route::get('/', [InboxController::class, 'index']);
            Route::get('/unread-count', [InboxController::class, 'unreadCount']);
            Route::get('/search-bacheliers', [InboxController::class, 'searchBacheliers']);
            Route::post('/conversations', [InboxController::class, 'startConversation']);
            Route::get('/conversations/{conversationId}', [InboxController::class, 'show']);
            Route::post('/conversations/{conversationId}/reply', [InboxController::class, 'reply']);
            Route::post('/conversations/{conversationId}/archive', [InboxController::class, 'archive']);
            Route::delete('/conversations/{conversationId}', [InboxController::class, 'destroy']);
        });
    });
});

/*
|--------------------------------------------------------------------------
| Route par défaut de l'API
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'API PEUB - Plateforme d\'Excellence Universitaire du Bénin',
        'version' => '1.0.0',
        'endpoints' => [
            'auth' => '/api/auth',
            'bachelier' => '/api/bachelier',
            'opportunites' => '/api/opportunites',
            'library' => '/api/bachelier/library',
            'forum' => '/api/bachelier/forum',
            'inbox' => '/api/bachelier/inbox',
        ],
        'documentation' => url('/api/documentation')
    ]);
});

/*
|--------------------------------------------------------------------------
| Gestion des erreurs 404
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route API non trouvée',
        'error' => 'La ressource demandée n\'existe pas'
    ], 404);
});








