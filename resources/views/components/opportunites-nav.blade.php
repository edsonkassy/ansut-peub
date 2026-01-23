{{-- Navigation Pills pour les pages d'opportunités --}}
<nav class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
    <a href="{{ route('bachelier.opportunites') }}" 
       class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap {{ request()->routeIs('bachelier.opportunites') && !request()->has('filter') ? 'bg-[#0E7490] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 active:bg-gray-300' }}"
       style="touch-action: manipulation;">
        <div class="flex items-center space-x-2">
            <i data-lucide="briefcase" class="w-4 h-4"></i>
            <span>Toutes les opportunités</span>
        </div>
    </a>
    
    <a href="{{ route('bachelier.favoris') }}" 
       class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap {{ request()->routeIs('bachelier.favoris') ? 'bg-[#0E7490] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 active:bg-gray-300' }}"
       style="touch-action: manipulation;">
        <div class="flex items-center space-x-2">
            <i data-lucide="heart" class="w-4 h-4"></i>
            <span>Mes favoris</span>
        </div>
    </a>
    
    <a href="{{ route('bachelier.candidatures') }}" 
       class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap {{ request()->routeIs('bachelier.candidatures') ? 'bg-[#0E7490] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 active:bg-gray-300' }}"
       style="touch-action: manipulation;">
        <div class="flex items-center space-x-2">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            <span>Mes candidatures</span>
        </div>
    </a>
</nav>

