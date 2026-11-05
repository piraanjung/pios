@props([
    'title' => null,
    'subtitle' => null,
    'action' => null,
    'footer' => null,
    'shadow' => 'shadow-blur',
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'card ' . $shadow . ' ' . $class]) }}>
    @if ($title || $action)
        <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
            <div>
                @if ($title)
                    <h6 class="mb-0 text-capitalize font-weight-bold">{{ $title }}</h6>
                @endif
                @if ($subtitle)
                    <p class="text-sm mb-0 text-secondary">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($action)
                <div>{{ $action }}</div>
            @endif
        </div>
    @endif
    <div class="card-body p-3">
        {{ $slot }}
    </div>
    @if ($footer)
        <div class="card-footer p-3 bg-transparent border-top">
            {{ $footer }}
        </div>
    @endif
</div>
