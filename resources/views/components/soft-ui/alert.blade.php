@props([
    'color' => 'success',
    'dismissible' => true,
    'icon' => null,
    'class' => '',
])

<div {{ $attributes->merge(['class' => "alert alert-{$color} text-white font-weight-bold " . ($dismissible ? 'alert-dismissible fade show ' : '') . $class]) }} role="alert">
    @if ($icon)
        <span class="alert-icon"><i class="{{ $icon }} me-2"></i></span>
    @endif
    <span class="alert-text">{{ $slot }}</span>
    @if ($dismissible)
        <button type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
</div>
