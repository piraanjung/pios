@extends('annual_trash.layout')

@section('page-topic', 'บันทึกผลการจัดเก็บ')
@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-1">ถัง {{ $bin->bin_code }}</h6>
                    <p class="text-sm text-secondary mb-0">{{ $bin->user->address ?? '' }} {{ $bin->location_description }}</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('keptkayas.annual_trash.collection.store', $bin) }}" enctype="multipart/form-data">
                        @csrf
                        <label for="outcome" class="form-label">ผลการเข้าจุด</label>
                        <select class="form-select mb-3" id="outcome" name="outcome" required>
                            <option value="collected">เก็บแล้ว</option>
                            <option value="missed">เข้าไม่ถึงจุด</option>
                            <option value="issue">พบปัญหา</option>
                        </select>
                        <label for="notes" class="form-label">รายละเอียด</label>
                        <textarea class="form-control mb-3" id="notes" name="notes" rows="3" maxlength="1000"></textarea>
                        <label for="photo" class="form-label">ภาพประกอบ</label>
                        <input class="form-control mb-3" id="photo" type="file" name="photo" accept="image/*" capture="environment">
                        <button class="btn btn-primary mb-0" type="submit">บันทึกผล</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header pb-0"><h6>ประวัติจุดเก็บ</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead><tr><th>เวลา</th><th>ผล</th><th>เจ้าหน้าที่</th><th>รายละเอียด</th><th>รูป</th></tr></thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->visited_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ ['collected' => 'เก็บแล้ว', 'missed' => 'เข้าไม่ถึง', 'issue' => 'พบปัญหา'][$log->outcome] }}</td>
                                        <td>{{ $log->staff->firstname ?? '-' }}</td>
                                        <td>{{ $log->notes ?: '-' }}</td>
                                        <td>@if($log->photo_path)<a href="{{ asset('storage/'.$log->photo_path) }}" target="_blank">เปิดภาพ</a>@else-@endif</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">ยังไม่มีประวัติ</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection