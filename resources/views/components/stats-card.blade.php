{{-- Stats Card Component --}}
@props(['title', 'subtitle', 'value', 'valueLabel' => ''])

<div class="bg-[#0E7490] rounded-xl shadow-lg p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold mb-2">{{ $title }}</h2>
            <p class="text-white/80">{{ $subtitle }}</p>
        </div>
        <div class="text-right">
            <p class="text-3xl font-bold">{{ $value }}</p>
            @if($valueLabel)
            <p class="text-white/80 text-sm">{{ $valueLabel }}</p>
            @endif
        </div>
    </div>
</div>

