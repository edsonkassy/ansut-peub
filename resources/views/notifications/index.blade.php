@extends('layouts.bachelier')

@section('title', 'Notifications - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="NOTIFICATIONS" />

    <!-- Header Card -->
    <div class="bg-[#0E7490] rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold mb-2">Notifications</h2>
                <p class="text-white/80">Restez informé de toutes les actualités</p>
            </div>
            @if($notifications->where('read', false)->count() > 0)
            <button onclick="markAllAsRead()" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors">
                <i data-lucide="check-check" class="w-4 h-4"></i>
                Tout marquer comme lu
            </button>
            @endif
        </div>
    </div>

    <div>
        @if($notifications->count() > 0)
        <div class="space-y-4">
            @foreach($notifications as $notification)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 {{ !$notification->read ? 'border-l-4 border-l-[#0E7490] bg-[#0E7490]/5' : '' }}">
                <div class="flex items-start gap-4">
                    <!-- Icône -->
                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->read ? 'bg-gray-100 text-gray-500' : 'bg-[#0E7490]/10 text-[#0E7490]' }} flex-shrink-0">
                        <i data-lucide="{{ $notification->type_icon }}" class="w-5 h-5"></i>
                    </div>
                    
                    <!-- Contenu -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium {{ $notification->read ? 'text-gray-700' : 'text-gray-900' }}">
                                    {{ $notification->title }}
                                </h3>
                                <p class="text-sm {{ $notification->read ? 'text-gray-500' : 'text-gray-700' }} mt-1">
                                    {{ $notification->message }}
                                </p>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    @if(!$notification->read)
                                    <button onclick="markAsRead({{ $notification->id }})" 
                                            class="text-xs text-[#0E7490] hover:text-[#0E7490]/80 font-medium">
                                        Marquer comme lu
                                    </button>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-2 ml-4">
                                @if($notification->data && isset($notification->data['conversation_id']))
                                <a href="{{ route('bachelier.inbox.show', $notification->data['conversation_id']) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1 text-sm bg-[#0E7490] text-white hover:bg-[#0E7490]/90 rounded-lg transition-colors">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                    Voir
                                </a>
                                @elseif($notification->data && isset($notification->data['resource_id']))
                                <a href="{{ route('bachelier.library.show', $notification->data['resource_id']) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1 text-sm bg-[#0E7490] text-white hover:bg-[#0E7490]/90 rounded-lg transition-colors">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                    Voir
                                </a>
                                @elseif($notification->data && isset($notification->data['thread_id']))
                                <a href="{{ route('bachelier.forum.thread', $notification->data['thread_id']) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1 text-sm bg-[#0E7490] text-white hover:bg-[#0E7490]/90 rounded-lg transition-colors">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                    Voir
                                </a>
                                @endif
                                
                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1 text-gray-400 hover:text-red-600 rounded-lg transition"
                                            onclick="return confirm('Supprimer cette notification ?')">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $notifications->links() }}
        </div>
        @endif

        @else
        <!-- État vide -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
            <i data-lucide="bell" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune notification</h3>
            <p class="text-gray-600">Vous êtes à jour ! Vos notifications apparaîtront ici.</p>
        </div>
        @endif
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}
</script>
@endsection