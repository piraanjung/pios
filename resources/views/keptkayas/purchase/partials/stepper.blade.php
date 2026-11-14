@props(['currentStep' => 1])

@php
    $steps = [
        1 => ['title' => 'เลือกเขต', 'icon' => 'fas fa-map-marker-alt'],
        2 => ['title' => 'เลือกสมาชิก', 'icon' => 'fas fa-user-check'],
        3 => ['title' => 'ชั่งน้ำหนักขยะ', 'icon' => 'fas fa-weight'],
        4 => ['title' => 'สรุปและใบเสร็จ', 'icon' => 'fas fa-file-invoice-dollar'],
    ];
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-3 mx-2">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center position-relative">
            <div class="progress position-absolute w-100" style="height: 4px; z-index: 1; top: 50%; transform: translateY(-50%);">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ (($currentStep - 1) / 3) * 100 }}%"></div>
            </div>
            @foreach ($steps as $stepNum => $step)
                @php
                    $isPassed = $currentStep > $stepNum;
                    $isCurrent = $currentStep === $stepNum;
                    $bgClass = $isCurrent ? 'bg-primary text-white shadow' : ($isPassed ? 'bg-success text-white' : 'bg-light text-muted border');
                @endphp
                <div class="text-center position-relative" style="z-index: 2;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1 {{ $bgClass }}" style="width: 36px; height: 36px; font-weight: bold; font-size: 0.85rem;">
                        @if ($isPassed)
                            <i class="fas fa-check"></i>
                        @else
                            {{ $stepNum }}
                        @endif
                    </div>
                    <small class="d-block {{ $isCurrent ? 'fw-bold text-primary' : ($isPassed ? 'text-success' : 'text-muted') }}" style="font-size: 0.75rem;">
                        {{ $step['title'] }}
                    </small>
                </div>
            @endforeach
        </div>
    </div>
</div>
