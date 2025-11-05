
@props(['type' => 'info', 'title' => 'Thông báo'])

@php
    $colors = [
        'info' => 'bg-blue-100 border-blue-500 text-blue-700',
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'danger' => 'bg-red-100 border-red-500 text-red-700',
    ];
    $colorClass = $colors[$type] ?? 'bg-gray-100 border-gray-500 text-gray-700';
@endphp

<div {{ $attributes->merge(['class' => 'border-l-4 p-4 ' . $colorClass]) }} role="alert">
    <p class="font-bold">{{ $title }}</p>
    <p>{{ $slot }}</p>
</div>
