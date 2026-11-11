@props([
    'count' => 0,
    'label' => 'unread items',
    'alpine' => null,
])

@if($alpine)
    <span x-show="{{ $alpine }} > 0"
          x-text="{{ $alpine }} > 9 ? '9+' : {{ $alpine }}"
          x-cloak
          {{ $attributes->merge(['class' => 'absolute -top-0.5 -right-0.5 w-[18px] h-[18px] min-w-[18px] max-w-[18px] rounded-full bg-[var(--accent-primary)] text-[var(--accent-contrast,#ffffff)] text-[10px] font-bold leading-none flex items-center justify-center ring-2 ring-[var(--bg-surface)] select-none pointer-events-none transition transform']) }}
          aria-hidden="true">
    </span>
    <span class="sr-only" x-text="`${{ $alpine }} {{ $label }}`"></span>
@else
    @php
        $num = (int)$count;
        $display = $num > 9 ? '9+' : ($num > 0 ? (string)$num : null);
    @endphp
    @if($display)
        <span {{ $attributes->merge(['class' => 'absolute -top-0.5 -right-0.5 w-[18px] h-[18px] min-w-[18px] max-w-[18px] rounded-full bg-[var(--accent-primary)] text-[var(--accent-contrast,#ffffff)] text-[10px] font-bold leading-none flex items-center justify-center ring-2 ring-[var(--bg-surface)] select-none pointer-events-none transition transform']) }}
              aria-hidden="true">
            {{ $display }}
        </span>
        <span class="sr-only">{{ $count }} {{ $label }}</span>
    @endif
@endif

