@extends('layouts.bachelier')

@section('title', $thread->title . ' - Communauté PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="COMMUNAUTÉ / {{ strtoupper($thread->category->name) }} / DISCUSSION" />

    <div>
        <!-- Contenu du thread initial -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-[#00BFA5]/10 rounded-full flex items-center justify-center text-[#00BFA5] font-semibold">
                    {{ substr($thread->user->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">{{ $thread->user->name }}</span>
                            <span class="text-xs text-gray-500">Auteur</span>
                        </div>
                        <span class="text-sm text-gray-500">{{ $thread->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $thread->content }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des posts -->
        <div class="space-y-6">
            @forelse($posts as $post)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6" id="post-{{ $post->id }}">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 font-semibold">
                        {{ substr($post->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-900">{{ $post->user->name }}</span>
                                @if($post->is_edited)
                                    <span class="text-xs text-gray-500">(modifié {{ $post->edited_at->diffForHumans() }})</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">{{ $post->created_at->format('d/m/Y à H:i') }}</span>
                                @if($post->user_id === auth()->id())
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('bachelier.forum.edit-post', $post) }}" class="text-primary-600 hover:text-primary-800 text-sm">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('bachelier.forum.delete-post', $post) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                                    onclick="return confirm('Supprimer ce message ?')">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="prose max-w-none mb-3">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $post->content }}</p>
                        </div>
                        
                        <!-- Réactions sur le post -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1" data-reactions="{{ $post->id }}" data-type="App\Models\ForumPost">
                                @foreach(['like', 'love', 'wow', 'angry'] as $reactionType)
                                    @php
                                        $reactionData = \App\Models\ForumReaction::getReactionTypes()[$reactionType];
                                        $count = $post->reaction_counts[$reactionType] ?? 0;
                                        $userReaction = $post->user_reaction;
                                        $isActive = $userReaction && $userReaction->type === $reactionType;
                                    @endphp
                                    <button onclick="toggleReaction('{{ $post->id }}', 'App\\Models\\ForumPost', '{{ $reactionType }}')" 
                                            class="reaction-btn flex items-center gap-1 px-2 py-1 text-xs rounded-lg border hover:bg-gray-50 transition {{ $isActive ? 'bg-gray-100 border-gray-400 ' . $reactionData['color'] : 'border-gray-300 text-gray-600' }}"
                                            data-reaction="{{ $reactionType }}">
                                        <i data-lucide="{{ $reactionData['icon'] }}" class="w-3 h-3"></i>
                                        <span class="reaction-count">{{ $count > 0 ? $count : '' }}</span>
                                    </button>
                                @endforeach
                            </div>
                            
                            @if(!$thread->is_locked)
                            <button onclick="toggleReplyForm({{ $post->id }})" class="text-sm text-[#00BFA5] hover:text-[#00BFA5]/80 font-medium">
                                Répondre
                            </button>
                            @endif
                        </div>

                        <!-- Formulaire de réponse -->
                        @if(!$thread->is_locked)
                        <div id="reply-form-{{ $post->id }}" class="mt-4 hidden">
                            <form action="{{ route('bachelier.forum.store-post', $thread) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $post->id }}">
                                <div class="mb-3">
                                    <textarea name="content" rows="3" placeholder="Votre réponse..." required
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5]"></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="px-3 py-1 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 transition text-sm rounded-lg">
                                        Répondre
                                    </button>
                                    <button type="button" onclick="toggleReplyForm({{ $post->id }})" class="px-3 py-1 border border-gray-300 text-gray-700 hover:bg-gray-50 transition text-sm rounded-lg">
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Réponses -->
                        @if($post->replies->count() > 0)
                        <div class="mt-4 ml-6 space-y-4 border-l-2 border-gray-200 pl-4">
                            @foreach($post->replies as $reply)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-semibold text-sm">
                                        {{ substr($reply->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-medium text-gray-900 text-sm">{{ $reply->user->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $reply->content }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
                <i data-lucide="message-circle" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                <p class="text-gray-600">Aucune réponse pour le moment</p>
                <p class="text-sm text-gray-500 mt-1">Soyez le premier à répondre !</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $posts->links() }}
        </div>
        @endif

        <!-- Formulaire de réponse principal -->
        @if(!$thread->is_locked)
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Répondre à cette discussion</h3>
            <form id="main-reply-form" action="{{ route('bachelier.forum.store-post', $thread) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea name="content" rows="6" placeholder="Votre réponse..." required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                              minlength="5"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 transition font-medium rounded-lg">
                        <span class="button-text">Publier la réponse</span>
                        <span class="loading hidden">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin mr-2"></i>
                            Publication...
                        </span>
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
function toggleReaction(id, type, reactionType) {
    @auth
    fetch('{{ route("bachelier.forum.toggle-reaction") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            reactable_id: id,
            reactable_type: type,
            type: reactionType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const container = document.querySelector(`[data-reactions="${id}"][data-type="${type}"]`);
            const buttons = container.querySelectorAll('.reaction-btn');
            
            buttons.forEach(button => {
                const btnReactionType = button.dataset.reaction;
                const countSpan = button.querySelector('.reaction-count');
                const count = data.reactionCounts[btnReactionType] || 0;
                
                // Mettre à jour le compteur
                countSpan.textContent = count > 0 ? count : '';
                
                // Mettre à jour l'état actif
                if (btnReactionType === reactionType) {
                    const reactionData = {
                        'like': { color: 'text-primary-600' },
                        'love': { color: 'text-red-600' },
                        'wow': { color: 'text-primary-600' },
                        'angry': { color: 'text-orange-600' }
                    };
                    
                    if (data.hasReaction && data.reactionType === reactionType) {
                        button.className = button.className.replace('border-gray-300 text-gray-600', 'bg-gray-100 border-gray-400 ' + reactionData[reactionType].color);
                    } else {
                        button.className = button.className.replace(/bg-gray-100 border-gray-400 text-\w+-600/, 'border-gray-300 text-gray-600');
                    }
                } else {
                    // Désactiver les autres réactions
                    button.className = button.className.replace(/bg-gray-100 border-gray-400 text-\w+-600/, 'border-gray-300 text-gray-600');
                }
            });
        }
    });
    @else
    window.location.href = '{{ route("login") }}';
    @endauth
}

function toggleReplyForm(postId) {
    const form = document.getElementById(`reply-form-${postId}`);
    form.classList.toggle('hidden');
}

// Soumission dynamique du formulaire principal
document.addEventListener('DOMContentLoaded', function() {
    const mainForm = document.getElementById('main-reply-form');
    if (mainForm) {
        mainForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const buttonText = button.querySelector('.button-text');
            const loading = button.querySelector('.loading');
            const textarea = this.querySelector('textarea[name="content"]');
            
            // Désactiver le bouton et afficher le loading
            button.disabled = true;
            buttonText.classList.add('hidden');
            loading.classList.remove('hidden');
            
            // Préparer les données
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Vider le textarea
                    textarea.value = '';
                    
                    // Recharger la page pour afficher le nouveau message
                    window.location.reload();
                } else {
                    // Afficher l'erreur
                    alert(data.message || 'Erreur lors de la publication');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la publication');
            })
            .finally(() => {
                // Réactiver le bouton
                button.disabled = false;
                buttonText.classList.remove('hidden');
                loading.classList.add('hidden');
            });
        });
    }
});

function toggleFavorite(threadId) {
    @auth
    fetch(`/bachelier/forum/${threadId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.getElementById('favorite-btn');
            const icon = button.querySelector('i');
            const text = button.querySelector('.favorite-text');
            
            if (data.isFavorited) {
                button.className = button.className.replace('border-gray-300 text-gray-600', 'bg-red-50 border-red-300 text-red-600');
                icon.classList.add('fill-current');
                text.textContent = 'Favoris';
            } else {
                button.className = button.className.replace('bg-red-50 border-red-300 text-red-600', 'border-gray-300 text-gray-600');
                icon.classList.remove('fill-current');
                text.textContent = 'Ajouter aux favoris';
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
    @else
    window.location.href = '{{ route("login") }}';
    @endauth
}
</script>
@endsection