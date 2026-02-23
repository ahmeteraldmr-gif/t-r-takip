@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2.5 min-h-[48px] border-b-2 border-amber-500 text-base font-medium leading-6 text-amber-700 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-2.5 min-h-[48px] border-b-2 border-transparent text-base font-medium leading-6 text-gray-600 hover:text-amber-700 hover:border-amber-200 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
