@props([
    'title' => '',
    'value' => '0',
    'percentage' => null,
    'percentageColor' => 'text-success',
    'percentageText' => 'จากเดือนที่แล้ว',
    'icon' => 'ni ni-chart-bar-32',
    'gradient' => 'primary',
    'class' => '',
])

<div class="card {{ $class }}">
    <div class="card-body p-3">
        <div class="row">
            <div class="col-8">
                <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ $title }}</p>
                    <h5 class="font-weight-bolder mb-0">
                        {{ $value }}
                        @if ($percentage)
                            <span class="{{ $percentageColor }} text-sm font-weight-bolder">{{ $percentage }}</span>
                        @endif
                    </h5>
                    @if ($percentageText)
                        <small class="text-secondary text-xs">{{ $percentageText }}</small>
                    @endif
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-{{ $gradient }} shadow text-center border-radius-md d-flex align-items-center justify-content-center ms-auto">
                    <i class="{{ $icon }} text-lg opacity-10 text-white" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</div>
