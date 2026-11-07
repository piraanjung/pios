@props([
    'color' => 'primary',
    'gradient' => true,
    'size' => 'md',
    'outline' => false,
    'icon' => null,
    'type' => 'button',
    'class' => '',
])

@php
    $btnClass = 'btn mb-0 ';
    if ($outline) {
        $btnClass .= "btn-outline-{$color} ";
    } elseif ($gradient) {
        $btnClass .= "bg-gradient-{$color} ";
    } else {
        $btnClass .= "btn-{$color} ";
    }

    if ($size === 'sm') {
        $btnClass .= 'btn-sm ';
    } elseif ($size === 'lg') {
        $btnClass .= 'btn-lg ';
    }
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $btnClass . $class]) }}>
    @if ($icon)
        <i class="{{ $icon }} me-1" aria-hidden="true"></i>
    @endif
    {{ $slot }}
</button>
