@props(['status'])

@php
    $styles = match($status) {
        'pending' => 'bg-yellow-100 text-yellow-600 border border-yellow-200',
        'paid' => 'bg-blue-100 text-blue-600 border border-blue-200',
        'active' => 'bg-emerald-100 text-emerald-600 border border-emerald-200',
        'completed' => 'bg-slate-100 text-slate-600 border border-slate-200',
        'overdue' => 'bg-rose-100 text-rose-600 border border-rose-200',
        'cancelled' => 'bg-red-100 text-red-600 border border-red-200',
        default => 'bg-gray-100 text-gray-600 border border-gray-200'
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-bold capitalize $styles"]) }}>
    {{ $status }}
</span>