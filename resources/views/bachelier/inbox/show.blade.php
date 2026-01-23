@extends('layouts.bachelier')

@section('title', 'Conversation - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb & Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('bachelier.inbox.index') }}" 
               class="p-2 text-gray-600 hover:text-[#00BFA5] hover:bg-gray-100 rounded-lg transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <x-breadcrumb text="MESSAGERIE / CONVERSATION" />
        </div>
        
        <form action="{{ route('bachelier.inbox.archive', $conversation) }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition"
                    onclick="return confirm('Archiver cette conversation ?')"
                    title="Archiver">
                <i data-lucide="archive" class="w-4 h-4"></i>
                Archiver
            </button>
        </form>
    </div>

    <div>
        <!-- Messages -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6" style="height: 500px; overflow-y: auto;" id="messages-container">
            <div class="p-4 space-y-4">
                @forelse($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-start gap-3 max-w-xs lg:max-w-md {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                        <!-- Avatar -->
                        <div class="w-8 h-8 bg-{{ $message->sender_id === auth()->id() ? '[#00BFA5]/10' : 'gray-100' }} flex items-center justify-center text-{{ $message->sender_id === auth()->id() ? '[#00BFA5]' : 'gray-600' }} font-semibold text-sm flex-shrink-0 rounded-full">
                            {{ substr($message->sender->bachelier->prenoms ?? $message->sender->email, 0, 1) }}
                        </div>
                        
                        <!-- Message -->
                        <div class="flex flex-col {{ $message->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                            <div class="p-3 {{ $message->sender_id === auth()->id() 
                                    ? 'bg-[#00BFA5] text-white' 
                                    : 'bg-gray-100 text-gray-900' }} rounded-lg">
                                <p class="text-sm whitespace-pre-wrap">{{ $message->content }}</p>
                            </div>
                            <span class="text-xs text-gray-500 mt-1">
                                {{ $message->created_at->format('d/m/Y à H:i') }}
                                @if($message->sender_id === auth()->id() && $message->read_by_recipient)
                                    <i data-lucide="check-check" class="w-3 h-3 inline ml-1 text-green-500" title="Lu"></i>
                                @elseif($message->sender_id === auth()->id())
                                    <i data-lucide="check" class="w-3 h-3 inline ml-1" title="Envoyé"></i>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i data-lucide="message-circle" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                    <p class="text-gray-600">Aucun message dans cette conversation</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Formulaire de réponse -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4">
            <form action="{{ route('bachelier.inbox.store', $conversation) }}" method="POST" id="reply-form">
                @csrf
                <div class="flex gap-3">
                    <div class="w-8 h-8 bg-[#00BFA5]/10 flex items-center justify-center text-[#00BFA5] font-semibold text-sm flex-shrink-0 rounded-full">
                        {{ substr(auth()->user()->bachelier->prenoms ?? auth()->user()->email, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <textarea name="content" 
                                  rows="3" 
                                  placeholder="Écrivez votre message..."
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5] resize-none"
                                  maxlength="2000"></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-500">Appuyez sur Entrée pour envoyer</span>
                            <button type="submit" 
                                    class="flex items-center gap-2 px-4 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 transition text-sm rounded-lg">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Envoyer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    const replyForm = document.getElementById('reply-form');
    const contentTextarea = replyForm.querySelector('[name="content"]');

    // Scroll vers le bas
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Envoyer avec Entrée
    contentTextarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) {
                replyForm.submit();
            }
        }
    });

    // Auto-focus sur le textarea
    contentTextarea.focus();
});
</script>
@endsection