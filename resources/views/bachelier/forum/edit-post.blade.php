@extends('layouts.bachelier')

@section('title', 'Modifier le message - Communauté PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="COMMUNAUTÉ / MODIFIER LE MESSAGE" />

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <!-- Contexte du thread -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-[#00BFA5]/10 text-[#00BFA5]">
                        {{ $post->thread->category->name }}
                    </span>
                    @if($post->thread->is_locked)
                        <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full flex items-center gap-1">
                            <i data-lucide="lock" class="w-3 h-3"></i>
                            Verrouillé
                        </span>
                    @endif
                </div>
                <h3 class="font-medium text-gray-900 mb-1">{{ $post->thread->title }}</h3>
                <p class="text-sm text-gray-600">Discussion créée par {{ $post->thread->user->name }} · {{ $post->thread->created_at->diffForHumans() }}</p>
            </div>

            <form action="{{ route('bachelier.forum.update-post', $post) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Message original (si c'est une réponse) -->
                    @if($post->parent)
                    <div class="bg-teal-50 rounded-lg border border-teal-200 p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center text-teal-600 font-semibold text-sm">
                                {{ substr($post->parent->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-teal-900 text-sm">{{ $post->parent->user->name }}</span>
                                    <span class="text-xs text-teal-700">{{ $post->parent->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-teal-800 text-sm line-clamp-3">{{ $post->parent->content }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Contenu du message -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Votre message <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" id="content" rows="8" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5] @error('content') border-red-500 @enderror"
                                  placeholder="Modifier votre message...">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Minimum 5 caractères</p>
                    </div>

                    <!-- Conseils -->
                    <div class="bg-[#00BFA5]/10 rounded-lg border border-[#00BFA5]/20 p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Rappels pour un bon message :</h3>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Soyez respectueux et constructif dans vos propos</li>
                            <li>• Évitez les messages trop courts ou sans contenu</li>
                            <li>• Relisez votre message avant de le publier</li>
                            <li>• Les modifications sont visibles par tous les utilisateurs</li>
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <div class="text-sm text-gray-500">
                            Message original posté {{ $post->created_at->diffForHumans() }}
                            @if($post->is_edited)
                                · Dernière modification {{ $post->edited_at->diffForHumans() }}
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <a href="{{ route('bachelier.forum.thread', $post->thread) }}" 
                               class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 transition rounded-lg">
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 transition font-medium rounded-lg">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Aperçu du thread -->
        <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="font-medium text-gray-900 mb-4">Contexte de la discussion</h3>
            <div class="text-sm text-gray-600 line-clamp-4">
                {{ $post->thread->content }}
            </div>
            <div class="mt-3 flex items-center gap-4 text-xs text-gray-500">
                <span>{{ $post->thread->posts_count }} réponses</span>
                <span>•</span>
                <span>{{ $post->thread->views_count }} vues</span>
                @if($post->thread->tags && count($post->thread->tags) > 0)
                    <span>•</span>
                    <div class="flex gap-1">
                        @foreach(array_slice($post->thread->tags, 0, 3) as $tag)
                            <span class="px-1 py-0.5 bg-gray-100 rounded-full text-gray-600">#{{ $tag }}</span>
                        @endforeach
                        @if(count($post->thread->tags) > 3)
                            <span class="text-gray-500">+{{ count($post->thread->tags) - 3 }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection