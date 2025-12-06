@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-rose-50 duration-200 flex font-medium items-center px-4 py-3 rounded-xl text-rose-500 text-sm transition-colors'
            : 'flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors duration-200 text-gray-500 hover:bg-gray-50';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>