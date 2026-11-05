@props([
    'color' => 'primary',
    'gradient' => true,
    'size' => 'sm',
    'class' => '',
])

@php
    $badgeClass = 'badge ';
    $badgeClass .= $size === 'sm' ? 'badge-sm ' : '';
    $badgeClass .= $gradient ? "bg-gradient-{$color} " : "badge-{$color} ";
@endphp

<span {{ $attributes->merge(['class' => $badgeClass . $class]) }}>
    {{ $slot }}
</span>
