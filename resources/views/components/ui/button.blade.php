@props ([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'block' => false,
    'type' => 'button',
])

@php
    $variantClasses = [
        'primary' => 'bg-green-500 text-white hover:bg-green-600 dark:bg-neon dark:text-black dark:hover:bg-[#00cc6a]',
        'secondary' => 'border border-gray-200 bg-gray-50 text-gray-700 hover:border-green-300 hover:text-green-600 dark:border-gray-800 dark:bg-darkBg dark:text-gray-300 dark:hover:border-neon/40 dark:hover:text-neon',
        'danger' => 'border border-red-700 bg-red-600 text-white hover:bg-red-700',
        'warning' => 'border border-yellow-300 bg-yellow-400 text-black hover:bg-yellow-300',
        'ghost' => 'text-gray-600 hover:bg-gray-100 hover:text-green-600 dark:text-gray-300 dark:hover:bg-neon/10 dark:hover:text-neon',
    ];

    $sizeClasses = [
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-3 text-base sm:text-lg',
    ];

    $classes = [
        'inline-flex items-center justify-center gap-2 rounded-xl font-semibold shadow-sm transition-all',
        'disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none',
        $variantClasses[$variant] ?? $variantClasses['primary'],
        $sizeClasses[$size] ?? $sizeClasses['md'],
        'w-full' => $block,
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>{{ $slot }}</button>
@endif
