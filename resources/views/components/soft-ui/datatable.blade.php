@props([
    'id' => 'datatable-' . uniqid(),
    'headers' => [],
    'actions' => null,
])

<div class="card shadow-blur">
    @if ($actions)
        <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bolder">รายการข้อมูล</h6>
            <div>{{ $actions }}</div>
        </div>
    @endif
    <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-3">
            <table class="table align-items-center mb-0" id="{{ $id }}">
                <thead>
                    <tr>
                        @foreach ($headers as $header)
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</div>
