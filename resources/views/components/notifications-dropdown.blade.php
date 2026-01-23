<div class="relative" x-data="{ 
    open: false, 
    notifications: [],
    unreadCount: 0,
    async loadNotifications() {
        try {
            const response = await fetch('/notifications/unread');
            const data = await response.json();
            this.notifications = data.notifications;
            this.unreadCount = data.unread_count;
        } catch (error) {
            console.error('Erreur lors du chargement des notifications:', error);
        }
    },
    async markAsRead(notificationId) {
        try {
            await fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            this.loadNotifications();
        } catch (error) {
            console.error('Erreur:', error);
        }
    }
}" 
@close-dropdowns.window="open = false"
x-init="loadNotifications(); setInterval(() => loadNotifications(), 30000)">
    
    <button @click="open = !open; if(open) loadNotifications()" 
            class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors relative rounded-lg">
        <span class="sr-only">Voir les notifications</span>
        <i data-lucide="bell" class="h-6 w-6"></i>
        <!-- Notification Badge -->
        <span x-show="unreadCount > 0" 
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] flex items-center justify-center bg-red-500 text-white text-xs font-medium rounded-full px-1">
        </span>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         @click.away="open = false" 
         class="absolute right-0 mt-2 w-80 bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 rounded-xl max-h-96 overflow-hidden">
        
        <div class="p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
                <a href="{{ route('notifications.index') }}" 
                   @click.stop 
                   class="text-primary-600 hover:text-primary-700 font-medium transition-colors" title="Voir toutes les notifications" aria-label="Voir toutes les notifications">
                    <i data-lucide="eye" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
        
        <div class="max-h-80 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="p-6 text-center text-gray-500">
                    <i data-lucide="bell" class="w-8 h-8 mx-auto mb-2 text-gray-400"></i>
                    <p class="text-sm">Aucune notification</p>
                </div>
            </template>
            
            <template x-for="notification in notifications" :key="notification.id">
                <div class="p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-primary-100 flex-shrink-0 rounded-lg">
                            <i :data-lucide="notification.icon" :class="notification.color" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                            <p class="text-sm text-gray-600 line-clamp-2" x-text="notification.message"></p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs text-gray-400" x-text="notification.created_at"></span>
                                <button @click="markAsRead(notification.id)" 
                                        class="text-xs text-primary-600 hover:text-primary-800">
                                    Marquer lu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
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