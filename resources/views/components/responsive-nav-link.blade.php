@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-3 py-2.5 rounded-lg text-sm font-medium text-gray-900 bg-amber-50 text-amber-900'
            : 'block w-full px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
