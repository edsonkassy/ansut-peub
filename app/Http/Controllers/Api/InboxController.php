<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\BachelierConversation;
use App\Models\BachelierMessage;
use App\Models\User;

class InboxController extends Controller
{
    /**
     * Liste des conversations de l'utilisateur
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $conversations = BachelierConversation::where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('recipient_id', $userId);
        })
        ->where('is_archived', false)
        ->with(['user.bachelier', 'recipient.bachelier', 'lastMessage'])
        ->withCount(['messages as unread_count' => function($q) use ($userId) {
            $q->where('recipient_id', $userId)
              ->whereNull('read_at');
        }])
        ->orderByDesc('last_message_at')
        ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $conversations
        ], 200);
    }

    /**
     * Détails d'une conversation avec messages
     * 
     * @param Request $request
     * @param int $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $conversationId)
    {
        $userId = $request->user()->id;

        $conversation = BachelierConversation::where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('recipient_id', $userId);
        })
        ->with(['user.bachelier', 'recipient.bachelier'])
        ->find($conversationId);

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation non trouvée'
            ], 404);
        }

        // Récupérer les messages
        $messages = BachelierMessage::where('conversation_id', $conversation->id)
            ->with(['sender.bachelier'])
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        // Marquer les messages comme lus
        BachelierMessage::where('conversation_id', $conversation->id)
            ->where('recipient_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'data' => [
                'conversation' => $conversation,
                'messages' => $messages
            ]
        ], 200);
    }

    /**
     * Créer une nouvelle conversation
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function startConversation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = $request->user()->id;
        $recipientId = $request->recipient_id;

        // Vérifier que le destinataire existe et est un bachelier
        $recipient = User::where('id', $recipientId)
            ->where('role', 'bachelier')
            ->first();

        if (!$recipient) {
            return response()->json([
                'success' => false,
                'message' => 'Destinataire non trouvé ou non autorisé'
            ], 404);
        }

        // Vérifier qu'on n'essaie pas de s'envoyer un message à soi-même
        if ($userId === $recipientId) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas vous envoyer un message à vous-même'
            ], 422);
        }

        // Vérifier si une conversation existe déjà
        $existingConversation = BachelierConversation::where(function($q) use ($userId, $recipientId) {
            $q->where('user_id', $userId)->where('recipient_id', $recipientId);
        })->orWhere(function($q) use ($userId, $recipientId) {
            $q->where('user_id', $recipientId)->where('recipient_id', $userId);
        })->first();

        if ($existingConversation) {
            // Si une conversation existe, ajouter le message
            $message = BachelierMessage::create([
                'conversation_id' => $existingConversation->id,
                'sender_id' => $userId,
                'recipient_id' => $recipientId,
                'content' => $request->message,
            ]);

            $existingConversation->update(['last_message_at' => now()]);

            $existingConversation->load(['user.bachelier', 'recipient.bachelier', 'lastMessage']);

            return response()->json([
                'success' => true,
                'message' => 'Message envoyé',
                'data' => [
                    'conversation' => $existingConversation,
                    'message' => $message
                ]
            ], 200);
        }

        // Créer une nouvelle conversation
        $conversation = BachelierConversation::create([
            'user_id' => $userId,
            'recipient_id' => $recipientId,
            'last_message_at' => now(),
        ]);

        // Créer le premier message
        $message = BachelierMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'recipient_id' => $recipientId,
            'content' => $request->message,
        ]);

        $conversation->load(['user.bachelier', 'recipient.bachelier', 'lastMessage']);

        return response()->json([
            'success' => true,
            'message' => 'Conversation créée avec succès',
            'data' => [
                'conversation' => $conversation,
                'message' => $message
            ]
        ], 201);
    }

    /**
     * Envoyer un message dans une conversation existante
     * 
     * @param Request $request
     * @param int $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function reply(Request $request, $conversationId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = $request->user()->id;

        $conversation = BachelierConversation::where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('recipient_id', $userId);
        })->find($conversationId);

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation non trouvée'
            ], 404);
        }

        // Déterminer le destinataire
        $recipientId = $conversation->user_id === $userId 
            ? $conversation->recipient_id 
            : $conversation->user_id;

        $message = BachelierMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'recipient_id' => $recipientId,
            'content' => $request->message,
        ]);

        $conversation->update(['last_message_at' => now()]);

        $message->load(['sender.bachelier']);

        return response()->json([
            'success' => true,
            'message' => 'Message envoyé',
            'data' => $message
        ], 201);
    }

    /**
     * Archiver une conversation
     * 
     * @param Request $request
     * @param int $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function archive(Request $request, $conversationId)
    {
        $userId = $request->user()->id;

        $conversation = BachelierConversation::where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('recipient_id', $userId);
        })->find($conversationId);

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation non trouvée'
            ], 404);
        }

        $conversation->update(['is_archived' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Conversation archivée'
        ], 200);
    }

    /**
     * Supprimer une conversation
     * 
     * @param Request $request
     * @param int $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $conversationId)
    {
        $userId = $request->user()->id;

        $conversation = BachelierConversation::where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('recipient_id', $userId);
        })->find($conversationId);

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation non trouvée'
            ], 404);
        }

        DB::transaction(function() use ($conversation) {
            // Supprimer tous les messages
            BachelierMessage::where('conversation_id', $conversation->id)->delete();
            
            // Supprimer la conversation
            $conversation->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Conversation supprimée'
        ], 200);
    }

    /**
     * Rechercher des bacheliers pour démarrer une conversation
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchBacheliers(Request $request)
    {
        $search = $request->get('search', '');
        $userId = $request->user()->id;

        $bacheliers = User::where('role', 'bachelier')
            ->where('status', 'active')
            ->where('id', '!=', $userId)
            ->whereHas('bachelier', function($q) use ($search) {
                if (!empty($search)) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenoms', 'like', "%{$search}%");
                }
            })
            ->with('bachelier')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bacheliers
        ], 200);
    }

    /**
     * Nombre de messages non lus
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unreadCount(Request $request)
    {
        $userId = $request->user()->id;

        $count = BachelierMessage::where('recipient_id', $userId)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count
            ]
        ], 200);
    }
}








