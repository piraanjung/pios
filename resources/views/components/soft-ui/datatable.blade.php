@props([
    'id' => 'datatable-' . uniqid(),
    'headers' => [],
    'title' => 'รายการข้อมูล',
    'actions' => null,
])

<div class="card shadow-blur">
    @if ($title || $actions)
        <div class="card-header pb-0 p-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
            @if ($title)
                <h6 class="mb-0 font-weight-bolder text-nowrap">{{ $title }}</h6>
            @endif
            @if ($actions)
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto justify-content-start justify-content-sm-end">{{ $actions }}</div>
            @endif
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
