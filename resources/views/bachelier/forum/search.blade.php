@extends('layouts.bachelier')

@section('title', 'Recherche - Communauté PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="COMMUNAUTÉ / RECHERCHE" />

    <div>
        <!-- Formulaire de recherche -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('bachelier.forum.search') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label for="q" class="block text-sm font-medium text-gray-700 mb-2">
                            Rechercher
                        </label>
                        <input type="text" name="q" id="q" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                               value="{{ request('q') }}" 
                               placeholder="Tapez votre recherche...">
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Catégorie
                        </label>
                        <select name="category" id="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5]">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 transition font-medium rounded-lg">
                        <i data-lucide="search" class="w-4 h-4 inline mr-2"></i>
                        Rechercher
                    </button>
                </div>
            </form>
        </div>

        <!-- Résultats -->
        @if(request('q'))
        <div class="bg-white border border-gray-200">
            <div class="px-6 py-4 ">
                <h2 class="text-lg font-semibold text-gray-900">
                    Résultats pour "{{ request('q') }}"
                    @if($threads->total() > 0)
                        <span class="text-gray-500 font-normal">({{ $threads->total() }} résultats)</span>
                    @endif
                </h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($threads as $thread)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $thread->category->name }}
                                </span>
                                @if($thread->is_pinned)
                                    <span class="px-2 py-1 text-xs font-semibold bg-primary-100 text-primary-800 flex items-center gap-1">
                                        <i data-lucide="pin" class="w-3 h-3"></i>
                                        Épinglé
                                    </span>
                                @endif
                                @if($thread->is_featured)
                                    <span class="px-2 py-1 text-xs font-semibold bg-primary-100 text-primary-800 flex items-center gap-1">
                                        <i data-lucide="star" class="w-3 h-3"></i>
                                        Vedette
                                    </span>
                                @endif
                                @if($thread->is_locked)
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 flex items-center gap-1">
                                        <i data-lucide="lock" class="w-3 h-3"></i>
                                        Verrouillé
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="font-medium text-gray-900 hover:text-primary-600 mb-1">
                                <a href="{{ route('bachelier.forum.thread', $thread) }}">
                                    {!! str_replace(request('q'), '<mark class="bg-primary-100 text-primary-800">' . request('q') . '</mark>', $thread->title) !!}
                                </a>
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                {!! str_replace(request('q'), '<mark class="bg-primary-100 text-primary-800">' . request('q') . '</mark>', Str::limit($thread->content, 200)) !!}
                            </p>
                            
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span>Par <span class="font-medium">{{ $thread->user->name }}</span></span>
                                <span>•</span>
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                                @if($thread->last_post)
                                    <span>•</span>
                                    <span>Dernière réponse: {{ $thread->last_post->created_at->diffForHumans() }}</span>
                                @endif
                            </div>

                            @if($thread->tags && count($thread->tags) > 0)
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($thread->tags as $tag)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs">#{{ $tag }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        
                        <div class="text-right text-sm text-gray-500 ml-4">
                            <div class="font-medium">{{ $thread->posts_count }}</div>
                            <div class="text-xs">réponses</div>
                            <div class="font-medium mt-1">{{ $thread->views_count }}</div>
                            <div class="text-xs">vues</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <i data-lucide="search" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun résultat trouvé</h3>
                    <p class="text-gray-600 mb-4">Aucune discussion ne correspond à votre recherche.</p>
                    <div class="text-sm text-gray-500">
                        <p>Suggestions :</p>
                        <ul class="mt-2 space-y-1">
                            <li>• Vérifiez l'orthographe de vos mots-clés</li>
                            <li>• Essayez des termes plus généraux</li>
                            <li>• Essayez des mots-clés différents</li>
                        </ul>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($threads->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $threads->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="bg-white border border-gray-200 p-12 text-center">
            <i data-lucide="search" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Rechercher dans les discussions</h3>
            <p class="text-gray-600">Utilisez le formulaire ci-dessus pour rechercher dans les discussions et messages du forum.</p>
        </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

mark {
    padding: 1px 2px;
    border-radius: 2px;
}
</style>
@endsection