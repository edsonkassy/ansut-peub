@extends('layouts.bachelier')

@section('title', $category->name . ' - Communauté PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="COMMUNAUTÉ / {{ strtoupper($category->name) }}" />

    <div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 ">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Discussions ({{ $threads->total() }})
                    </h2>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($threads as $thread)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                @if($thread->is_pinned)
                                    <span class="px-2 py-1 text-xs font-semibold bg-[#00BFA5]/10 text-[#00BFA5] rounded-full flex items-center gap-1">
                                        <i data-lucide="pin" class="w-3 h-3"></i>
                                        Épinglé
                                    </span>
                                @endif
                                @if($thread->is_featured)
                                    <span class="px-2 py-1 text-xs font-semibold bg-[#00BFA5]/10 text-[#00BFA5] rounded-full flex items-center gap-1">
                                        <i data-lucide="star" class="w-3 h-3"></i>
                                        Vedette
                                    </span>
                                @endif
                                @if($thread->is_locked)
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full flex items-center gap-1">
                                        <i data-lucide="lock" class="w-3 h-3"></i>
                                        Verrouillé
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="font-medium text-gray-900 hover:text-[#00BFA5] mb-1">
                                <a href="{{ route('bachelier.forum.thread', $thread) }}" class="text-black hover:text-[#00BFA5]">{{ $thread->title }}</a>
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ Str::limit($thread->content, 150) }}</p>
                            
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
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">#{{ $tag }}</span>
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
                    <i data-lucide="message-circle" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune discussion</h3>
                    <p class="text-gray-600 mb-4">Il n'y a pas encore de discussions dans cette catégorie.</p>
                    <a href="{{ route('bachelier.forum.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 transition rounded-lg">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Retour aux discussions
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($threads->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $threads->links() }}
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
</style>
@endsection