@extends('annual_trash.layout')

@section('page-topic', 'รายละเอียดถังขยะ')
@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">ถัง {{ $AnnualTrash->bin_code }}</h6>
                <p class="text-sm text-secondary mb-0">{{ $AnnualTrash->user->firstname }} {{ $AnnualTrash->user->lastname }}</p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-primary btn-sm mb-0" href="{{ route('keptkayas.annual_trash.collection.show', $AnnualTrash) }}">บันทึกงานสนาม</a>
                <a class="btn btn-secondary btn-sm mb-0" href="{{ route('keptkayas.waste_bins.index', $AnnualTrash->user_id) }}">กลับรายการ</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">ประเภท</dt><dd class="col-sm-9">{{ $AnnualTrash->kpUserGroup->usergroup_name ?? $AnnualTrash->bin_type }}</dd>
                <dt class="col-sm-3">ตำแหน่ง</dt><dd class="col-sm-9">{{ $AnnualTrash->location_description ?: '-' }}</dd>
                <dt class="col-sm-3">พิกัด</dt><dd class="col-sm-9">{{ $AnnualTrash->latitude ?? '-' }}, {{ $AnnualTrash->longitude ?? '-' }}</dd>
                <dt class="col-sm-3">สถานะถัง</dt><dd class="col-sm-9">{{ $AnnualTrash->status }}</dd>
                <dt class="col-sm-3">เข้าร่วมจัดเก็บรายปี</dt><dd class="col-sm-9">{{ $AnnualTrash->is_active_for_annual_collection ? 'ใช่' : 'ไม่ใช่' }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection