@extends('layouts.bachelier')

@section('title', $resource->title . ' - Bibliothèque PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="RESSOURCES / {{ strtoupper($resource->category->name) }} / {{ strtoupper($resource->title) }}" />

    <div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2">
                <!-- En-tête de la ressource -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                    <div class="flex flex-wrap items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 uppercase">{{ $resource->type }}</span>
                                @if($resource->is_featured)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Vedette</span>
                                @endif
                                @if($resource->level)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-800 capitalize">{{ $resource->level }}</span>
                                @endif
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $resource->title }}</h1>
                            <div class="flex items-center text-sm text-gray-600 space-x-4">
                                <span>Par {{ $resource->author ?? $resource->user->name }}</span>
                                <span>·</span>
                                <span>{{ $resource->published_at->diffForHumans() }}</span>
                                @if($resource->duration)
                                    <span>·</span>
                                    <span>{{ $resource->duration }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-2 mt-4 lg:mt-0">
                            <button onclick="toggleFavorite({{ $resource->id }})" 
                                    class="flex items-center gap-1 px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition {{ $isFavorited ? 'text-red-600 border-red-300 bg-red-50' : 'text-gray-700' }}"
                                    id="favorite-btn">
                                <i data-lucide="heart" class="w-4 h-4 {{ $isFavorited ? 'fill-current' : '' }}"></i>
                                <span id="favorite-text">{{ $isFavorited ? 'Favoris' : 'Ajouter' }}</span>
                            </button>
                            
                            <button onclick="toggleLike({{ $resource->id }})" 
                                    class="flex items-center gap-1 px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition {{ $isLiked ? 'text-teal-600 border-teal-300 bg-teal-50' : 'text-gray-700' }}"
                                    id="like-btn">
                                <i data-lucide="thumbs-up" class="w-4 h-4 {{ $isLiked ? 'fill-current' : '' }}"></i>
                                <span id="like-count">{{ $resource->likes_count }}</span>
                            </button>
                        </div>
                    </div>

                    <p class="text-gray-700 leading-relaxed mb-6">{{ $resource->description }}</p>

                    @if($resource->tags && count($resource->tags) > 0)
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach($resource->tags as $tag)
                                <span class="px-2 py-1 bg-gray-100 rounded-full text-gray-700 text-sm">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Actions principales -->
                    <div class="flex flex-wrap gap-3">
                        @if($resource->file_path)
                            <a href="{{ route('bachelier.library.download', $resource) }}" 
                               class="flex items-center gap-2 px-6 py-3 bg-primary-600 text-white hover:bg-primary-700 transition font-medium rounded-md">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Télécharger ({{ $resource->file_size_formatted }})
                            </a>
                        @endif
                        
                        @if($resource->external_url)
                            <a href="{{ $resource->external_url }}" target="_blank" 
                               class="flex items-center gap-2 px-6 py-3 bg-green-600 text-white hover:bg-green-700 transition font-medium rounded-md">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                Ouvrir le lien
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Aperçu intégré selon le type -->
                @php
                    $type = strtolower($resource->type ?? '');
                    $fileUrl = $resource->file_path ? Storage::url($resource->file_path) : null;
                    $externalUrl = $resource->external_url ?? null;
                    $isYouTube = $externalUrl && preg_match('/(youtube\\.com|youtu\\.be)/i', $externalUrl);
                    $youTubeEmbed = null;
                    if ($isYouTube) {
                        $host = parse_url($externalUrl, PHP_URL_HOST);
                        $path = parse_url($externalUrl, PHP_URL_PATH);
                        $query = parse_url($externalUrl, PHP_URL_QUERY);
                        $videoId = null;

                        if ($host && stripos($host, 'youtu.be') !== false) {
                            $videoId = ltrim($path ?? '', '/');
                        } elseif ($host && stripos($host, 'youtube.com') !== false) {
                            if (strpos($path ?? '', '/watch') === 0) {
                                parse_str($query ?? '', $params);
                                $videoId = $params['v'] ?? null;
                            } elseif (strpos($path ?? '', '/embed/') === 0) {
                                $videoId = trim(substr($path, strlen('/embed/')));
                            }
                        }

                        if ($videoId) {
                            $youTubeEmbed = 'https://www.youtube.com/embed/' . $videoId;
                        }
                    }
                @endphp

                @if($type === 'pdf' && $fileUrl)
                <div class="bg-white border border-gray-200 p-0 mb-6">
                    <iframe src="{{ $fileUrl }}#navpanes=0&pagemode=none&toolbar=1" class="w-full h-[80vh]" title="Aperçu PDF"></iframe>
                </div>
                @elseif($type === 'video')
                    @if($isYouTube && $youTubeEmbed)
                    <div class="bg-white border border-gray-200 p-0 mb-6">
                        <div class="relative w-full" style="padding-bottom: 56.25%;">
                            <iframe src="{{ $youTubeEmbed }}" class="absolute inset-0 w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen title="Lecture vidéo"></iframe>
                        </div>
                    </div>
                    @elseif($fileUrl)
                    <div class="bg-white border border-gray-200 p-6 mb-6">
                        <video controls class="w-full max-h-[70vh] bg-black">
                            <source src="{{ $fileUrl }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                    </div>
                    @elseif($externalUrl)
                    <div class="bg-white border border-gray-200 p-6 mb-6">
                        <video controls class="w-full max-h-[70vh] bg-black" src="{{ $externalUrl }}"></video>
                    </div>
                    @endif
                @else
                    @if($resource->thumbnail)
                    <div class="bg-white border border-gray-200 p-6 mb-6">
                        <img src="{{ Storage::url($resource->thumbnail) }}" alt="{{ $resource->title }}" class="w-full max-h-96 object-cover">
                    </div>
                    @endif
                @endif

                <!-- Section des commentaires -->
                <div class="bg-white border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Commentaires</h3>
                    </div>

                    @auth
                    <!-- Erreur commentaire -->
                    <div id="commentError" class="mb-4 hidden">
                        <div class="flex items-start gap-2 px-3 py-2 text-sm text-red-700 bg-red-50 border border-red-200">
                            <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5"></i>
                            <span id="commentErrorText"></span>
                        </div>
                    </div>

                    <!-- Formulaire d'ajout de commentaire -->
                    <form id="commentForm" action="{{ route('bachelier.library.comments.store', $resource) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="mb-3">
                            <textarea name="content" id="commentContent" rows="3" placeholder="Ajouter un commentaire..." required
                                      class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>
                        <button type="button" id="commentSubmitBtn" onclick="submitComment()" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 transition font-medium">
                            <span id="commentSubmitText">Publier le commentaire</span>
                            <i data-lucide="loader" class="w-4 h-4 ml-2 animate-spin hidden" id="commentSubmitLoader"></i>
                        </button>
                    </form>
                    @endauth

                    <!-- Liste des commentaires -->
                    @if($comments->count() > 0)
                        <div class="space-y-6">
                            @foreach($comments as $comment)
                                <div class=" pb-6 last:border-0">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 bg-primary-100 flex items-center justify-center text-primary-600 font-semibold text-sm">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium text-gray-900 text-sm">{{ $comment->user->name }}</span>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-700 text-sm mb-3">{{ $comment->content }}</p>
                                            
                                            @auth
                                            <div class="flex items-center gap-4">
                                                <button onclick="toggleCommentLike({{ $comment->id }})" 
                                                        class="flex items-center gap-1 text-xs text-gray-500 hover:text-primary-600 transition"
                                                        id="comment-like-btn-{{ $comment->id }}">
                                                    <i data-lucide="thumbs-up" class="w-3 h-3 {{ $comment->isLikedBy(auth()->user()) ? 'fill-current text-primary-600' : '' }}"></i>
                                                    <span id="comment-like-count-{{ $comment->id }}">{{ $comment->likes_count }}</span>
                                                </button>
                                                <button onclick="toggleReply({{ $comment->id }})" class="text-xs text-gray-500 hover:text-primary-600 transition">
                                                    Répondre
                                                </button>
                                            </div>

                                            <!-- Formulaire de réponse -->
                                            <div id="reply-form-{{ $comment->id }}" class="mt-3 hidden">
                                                <form class="replyForm" action="{{ route('bachelier.library.comments.store', $resource) }}" method="POST" onsubmit="return false;">
                                                    @csrf
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    <div class="mb-2">
                                                        <textarea name="content" rows="2" placeholder="Votre réponse..." required
                                                                  class="w-full px-3 py-2 text-sm border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <button type="button" class="inline-flex items-center justify-center px-3 py-1 bg-primary-600 text-white hover:bg-primary-700 transition text-sm" onclick="submitReply(this)">
                                                            Répondre
                                                            <i data-lucide="loader" class="w-3.5 h-3.5 ml-2 animate-spin hidden"></i>
                                                        </button>
                                                        <button type="button" onclick="toggleReply({{ $comment->id }})" class="px-3 py-1 border border-gray-300 text-gray-700 hover:bg-gray-50 transition text-sm">
                                                            Annuler
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            @endauth

                                            <!-- Réponses -->
                                            @if($comment->replies->count() > 0)
                                                <div class="mt-4 ml-6 space-y-3">
                                                    @foreach($comment->replies as $reply)
                                                        <div class="flex items-start space-x-3">
                                                            <div class="w-6 h-6 bg-gray-100 flex items-center justify-center text-gray-600 font-semibold text-xs">
                                                                {{ substr($reply->user->name, 0, 1) }}
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="flex items-center gap-2 mb-1">
                                                                    <span class="font-medium text-gray-900 text-sm">{{ $reply->user->name }}</span>
                                                                    <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{ $comments->links() }}
                    @else
                        <div class="text-center py-8">
                            <i data-lucide="message-circle" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                            <p class="text-gray-600">Aucun commentaire pour cette ressource</p>
                            @auth
                                <p class="text-sm text-gray-500">Soyez le premier à commenter !</p>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statistiques -->
                <div class="bg-white border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Statistiques</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Vues</span>
                            <span class="font-medium">{{ number_format($resource->views_count) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Téléchargements</span>
                            <span class="font-medium">{{ number_format($resource->downloads_count) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Favoris</span>
                            <span class="font-medium">{{ number_format($resource->favorites_count) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">J'aime</span>
                            <span class="font-medium">{{ number_format($resource->likes_count) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Ressources similaires -->
                @if($relatedResources->count() > 0)
                <div class="bg-white border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Ressources similaires</h3>
                    <div class="space-y-4">
                        @foreach($relatedResources as $related)
                            <div class="flex items-start space-x-3">
                                @if($related->thumbnail)
                                    <img src="{{ Storage::url($related->thumbnail) }}" alt="{{ $related->title }}" class="w-12 h-12 object-cover">
                                @else
                                    <div class="w-12 h-12 bg-primary-100 flex items-center justify-center">
                                        <i data-lucide="book-open" class="w-5 h-5 text-primary-600"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-sm text-gray-900 line-clamp-2 mb-1">{{ $related->title }}</h4>
                                    <div class="text-xs text-gray-500">
                                        <span class="uppercase">{{ $related->type }}</span>
                                        · {{ $related->views_count }} vues
                                    </div>
                                    <a href="{{ route('bachelier.library.show', $related) }}" class="text-primary-600 hover:text-primary-800 text-xs font-medium">
                                        Voir →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleFavorite(resourceId) {
    @auth
    fetch(`/bachelier/library/${resourceId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById('favorite-btn');
        const icon = btn.querySelector('i');
        const text = document.getElementById('favorite-text');
        
        if (data.isFavorited) {
            btn.className = btn.className.replace('text-gray-700', 'text-red-600 border-red-300 bg-red-50');
            icon.classList.add('fill-current');
            text.textContent = 'Favoris';
        } else {
            btn.className = btn.className.replace('text-red-600 border-red-300 bg-red-50', 'text-gray-700');
            icon.classList.remove('fill-current');
            text.textContent = 'Ajouter';
        }
    });
    @else
    window.location.href = '{{ route("login") }}';
    @endauth
}

async function submitComment() {
    const form = document.getElementById('commentForm');
    const content = document.getElementById('commentContent');
    const btn = document.getElementById('commentSubmitBtn');
    const text = document.getElementById('commentSubmitText');
    const loader = document.getElementById('commentSubmitLoader');
    const errorBox = document.getElementById('commentError');
    const errorText = document.getElementById('commentErrorText');

    if (errorBox) { errorBox.classList.add('hidden'); errorText.textContent = ''; }

    if (!content.value.trim()) return;

    btn.disabled = true; text.textContent = 'Publication...'; loader.classList.remove('hidden');

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: new FormData(form)
        });
        let payload = null; try { payload = await res.json(); } catch(e){}
        if (!res.ok || (payload && payload.success === false)) {
            const message = (payload && payload.message) ? payload.message : "Une erreur est survenue lors de l'envoi du commentaire.";
            throw new Error(message);
        }
        // Reload to show new comment (simple approach)
        window.location.reload();
    } catch (err) {
        if (errorBox) { errorText.textContent = err.message; errorBox.classList.remove('hidden'); }
        else { alert(err.message); }
    } finally {
        btn.disabled = false; text.textContent = 'Publier le commentaire'; loader.classList.add('hidden');
    }
}

async function submitReply(button) {
    const form = button.closest('form');
    const loader = button.querySelector('i');
    const original = button.firstChild;

    button.disabled = true; loader.classList.remove('hidden');
    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: new FormData(form)
        });
        let payload = null; try { payload = await res.json(); } catch(e){}
        if (!res.ok || (payload && payload.success === false)) {
            const message = (payload && payload.message) ? payload.message : "Une erreur est survenue lors de l'envoi de la réponse.";
            throw new Error(message);
        }
        window.location.reload();
    } catch (err) {
        alert(err.message);
    } finally {
        button.disabled = false; loader.classList.add('hidden');
    }
}

function toggleLike(resourceId) {
    @auth
    fetch(`/bachelier/library/${resourceId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById('like-btn');
        const icon = btn.querySelector('i');
        const count = document.getElementById('like-count');
        
        if (data.isLiked) {
            btn.className = btn.className.replace('text-gray-700', 'text-teal-600 border-teal-300 bg-teal-50');
            icon.classList.add('fill-current');
        } else {
            btn.className = btn.className.replace('text-teal-600 border-teal-300 bg-teal-50', 'text-gray-700');
            icon.classList.remove('fill-current');
        }
        count.textContent = data.count;
    });
    @else
    window.location.href = '{{ route("login") }}';
    @endauth
}

function toggleCommentLike(commentId) {
    @auth
    fetch(`/bachelier/library/comments/${commentId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById(`comment-like-btn-${commentId}`);
        const icon = btn.querySelector('i');
        const count = document.getElementById(`comment-like-count-${commentId}`);
        
        if (data.isLiked) {
            icon.classList.add('fill-current', 'text-primary-600');
        } else {
            icon.classList.remove('fill-current', 'text-primary-600');
        }
        count.textContent = data.count;
    });
    @else
    window.location.href = '{{ route("login") }}';
    @endauth
}

function toggleReply(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.classList.toggle('hidden');
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection