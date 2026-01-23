<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\BachelierConversation;
use App\Models\BachelierMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Services\ModerationService;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Récupérer les conversations
        $conversations = BachelierConversation::with(['initiator.bachelier', 'participant.bachelier', 'latestMessage'])
            ->forUser($userId)
            ->notArchivedFor($userId)
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        // Enrichir les données avec l'autre participant et le nombre de messages non lus
        $conversations->getCollection()->transform(function ($conversation) use ($userId) {
            $conversation->other_participant = $conversation->getOtherParticipant($userId);
            $conversation->unread_count = $conversation->getUnreadCountFor($userId);
            $conversation->latest_message = $conversation->latestMessage->first();
            return $conversation;
        });

        return view('bachelier.inbox.index', compact('conversations'));
    }

    public function show(BachelierConversation $conversation)
    {
        $userId = Auth::id();

        // Vérifier que l'utilisateur fait partie de cette conversation
        if (!in_array($userId, [$conversation->initiator_id, $conversation->participant_id])) {
            abort(403, 'Vous n\'avez pas accès à cette conversation.');
        }

        // Marquer tous les messages comme lus
        $conversation->markAsReadFor($userId);

        // Charger les messages avec les utilisateurs
        $messages = $conversation->messages()->with('sender.bachelier')->get();
        
        $otherParticipant = $conversation->getOtherParticipant($userId);

        return view('bachelier.inbox.show', compact('conversation', 'messages', 'otherParticipant'));
    }

    public function store(Request $request, BachelierConversation $conversation)
    {
        $userId = Auth::id();

        // Vérifier que l'utilisateur fait partie de cette conversation
        if (!in_array($userId, [$conversation->initiator_id, $conversation->participant_id])) {
            abort(403, 'Vous n\'avez pas accès à cette conversation.');
        }

        $request->validate([
            'content' => 'required|string|max:2000'
        ]);

        // Moderation rapide (gpt-5-nano)
        $moderation = app(ModerationService::class)->allowText($request->content);
        if (!($moderation['allowed'] ?? true)) {
            return back()->with('error', "Ce contenu viole notre politique d'utilisation.")->withInput();
        }

        BachelierMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'content' => $request->content
        ]);

        return back()->with('success', 'Message envoyé avec succès !');
    }

    public function create(Request $request)
    {
        $search = $request->get('search', '');
        $bacheliers = collect();

        if (strlen($search) >= 2) {
            $bacheliers = User::with('bachelier')
                ->where('role', 'bachelier')
                ->where('id', '!=', Auth::id())
                ->where(function($query) use ($search) {
                    $query->whereHas('bachelier', function($q) use ($search) {
                        $q->where('prenoms', 'like', "%{$search}%")
                          ->orWhere('nom', 'like', "%{$search}%");
                    })->orWhere('email', 'like', "%{$search}%");
                })
                ->limit(10)
                ->get();
        }

        return view('bachelier.inbox.create', compact('bacheliers', 'search'));
    }

    public function startConversation(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string|max:2000'
        ]);

        $userId = Auth::id();
        $recipientId = $request->recipient_id;

        // Vérifier que le destinataire est bien un bachelier
        $recipient = User::where('id', $recipientId)->where('role', 'bachelier')->firstOrFail();

        // Créer ou récupérer la conversation
        $conversation = BachelierConversation::findOrCreateBetween($userId, $recipientId);

        // Moderation rapide (gpt-5-nano)
        $moderation = app(ModerationService::class)->allowText($request->content);
        if (!($moderation['allowed'] ?? true)) {
            $message = "Ce contenu viole notre politique d'utilisation.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message)->withInput();
        }

        // Créer le premier message
        BachelierMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'content' => $request->content
        ]);

        // Mettre à jour le sujet si fourni
        if ($request->filled('subject')) {
            $conversation->update(['subject' => $request->subject]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Conversation démarrée avec succès !',
                'conversation_id' => $conversation->id
            ]);
        }

        return redirect()->route('bachelier.inbox.show', $conversation)
            ->with('success', 'Conversation démarrée avec succès !');
    }

    public function searchBacheliers(Request $request)
    {
        $search = $request->get('q', '');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $bacheliers = User::with('bachelier')
            ->where('role', 'bachelier')
            ->where('id', '!=', Auth::id())
            ->where(function($query) use ($search) {
                $query->whereHas('bachelier', function($q) use ($search) {
                    $q->where('prenoms', 'like', "%{$search}%")
                      ->orWhere('nom', 'like', "%{$search}%");
                })->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->bachelier ? 
                        "{$user->bachelier->prenoms} {$user->bachelier->nom}" : 
                        $user->email,
                    'avatar' => substr($user->bachelier?->prenoms ?? $user->email, 0, 1),
                    'region' => $user->bachelier?->region ?? 'Non spécifiée'
                ];
            });

        return response()->json($bacheliers);
    }

    public function archive(BachelierConversation $conversation)
    {
        $userId = Auth::id();

        // Vérifier que l'utilisateur fait partie de cette conversation
        if (!in_array($userId, [$conversation->initiator_id, $conversation->participant_id])) {
            abort(403, 'Vous n\'avez pas accès à cette conversation.');
        }

        $conversation->archiveFor($userId);

        return back()->with('success', 'Conversation archivée.');
    }

    public function destroyConversation(BachelierConversation $conversation): JsonResponse
    {
        $userId = Auth::id();

        // Vérifier l'appartenance à la conversation
        if (!in_array($userId, [$conversation->initiator_id, $conversation->participant_id])) {
            return response()->json(['success' => false, 'message' => 'Accès refusé'], 403);
        }

        // Supprimer les messages puis la conversation
        $conversation->messages()->delete();
        $conversation->delete();

        return response()->json(['success' => true]);
    }

    public function getMessages(BachelierConversation $conversation)
    {
        $userId = Auth::id();

        // Vérifier que l'utilisateur fait partie de cette conversation
        if (!in_array($userId, [$conversation->initiator_id, $conversation->participant_id])) {
            return response()->json(['success' => false, 'message' => 'Accès refusé'], 403);
        }

        // Marquer tous les messages comme lus
        $conversation->markAsReadFor($userId);

        // Charger les messages avec les utilisateurs
        $messages = $conversation->messages()->with('sender.bachelier')->get()->map(function($message) use ($userId) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'created_at' => $message->created_at->format('H:i'),
                'is_sender' => $message->sender_id === $userId,
                'sender_name' => $message->sender->bachelier 
                    ? $message->sender->bachelier->prenoms . ' ' . $message->sender->bachelier->nom
                    : $message->sender->email
            ];
        });
        
        $otherParticipant = $conversation->getOtherParticipant($userId);

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'conversation' => [
                'id' => $conversation->id,
                'other_participant_name' => $otherParticipant->bachelier 
                    ? $otherParticipant->bachelier->prenoms . ' ' . $otherParticipant->bachelier->nom
                    : $otherParticipant->email,
                'other_participant_region' => $otherParticipant->bachelier?->region
            ]
        ]);
    }

    public function reply(Request $request, BachelierConversation $conversation)
    {
        $userId = Auth::id();

        // Vérifier que l'utilisateur fait partie de cette conversation
        if (!in_array($userId, [$conversation->initiator_id, $conversation->participant_id])) {
            return response()->json(['success' => false, 'message' => 'Accès refusé'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:2000'
        ]);

        // Moderation rapide (gpt-5-nano)
        $moderation = app(ModerationService::class)->allowText($request->content);
        if (!($moderation['allowed'] ?? true)) {
            return response()->json([
                'success' => false,
                'message' => "Ce contenu viole notre politique d'utilisation."
            ], 422);
        }

        $message = BachelierMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'content' => $request->content
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message envoyé avec succès !',
                'data' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'created_at' => $message->created_at->format('H:i'),
                    'is_sender' => true
                ]
            ]);
        }

        return back()->with('success', 'Message envoyé avec succès !');
    }

    public function searchUsers(Request $request)
    {
        $search = $request->get('q', '');
        
        if (strlen($search) < 2) {
            return response()->json(['users' => []]);
        }

        $users = User::with('bachelier')
            ->where('role', 'bachelier')
            ->where('id', '!=', Auth::id())
            ->where(function($query) use ($search) {
                $query->whereHas('bachelier', function($q) use ($search) {
                    $q->where('prenoms', 'like', "%{$search}%")
                      ->orWhere('nom', 'like', "%{$search}%");
                })->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'bachelier' => $user->bachelier ? [
                        'prenoms' => $user->bachelier->prenoms,
                        'nom' => $user->bachelier->nom,
                        'region' => $user->bachelier->region
                    ] : null,
                    'email' => $user->email
                ];
            });

        return response()->json(['users' => $users]);
    }

    public function destroyMessage(BachelierMessage $message): JsonResponse
    {
        $userId = Auth::id();

        // Seul l'expéditeur peut supprimer son propre message
        if ($message->sender_id !== $userId) {
            return response()->json(['success' => false, 'message' => 'Accès refusé'], 403);
        }

        $conversation = $message->conversation;

        $message->delete();

        // Mettre à jour la date du dernier message
        if ($conversation) {
            $last = $conversation->messages()->latest('created_at')->first();
            $conversation->update(['last_message_at' => $last?->created_at]);
        }

        return response()->json(['success' => true]);
    }
}