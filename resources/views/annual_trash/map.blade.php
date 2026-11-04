@extends('annual_trash.layout')

@section('nav-header', 'แผนที่ถังขยะ')
@section('nav-current', 'แผนที่แสดงถังขยะ')
@section('page-topic', 'แผนที่แสดงตำแหน่งถังขยะมูลฝอย')
@section('nav-waste_bins-viewmap', 'active')

@section('content')
    <div class="container-fluid py-3">
        <div class="mb-3">
            <p class="text-sm text-secondary mb-1">ทะเบียนถังขยะ / GIS</p>
            <h5 class="mb-0">ผูกถังกับสมาชิกบนแผนที่</h5>
            <span class="text-sm text-secondary">กดไอคอนถังเพื่อวางหลายจุด แล้วกด ✔ เพื่อเลือกสมาชิก · กดสีด้านล่างเพื่อกรองตามเหตุแจ้ง</span>
        </div>
        @include('partials.bin_map._ui')
    </div>
@endsection

@section('script')
    <script>
        window.binMapPopupExtra = (bin, esc) => `<div>ประเภท: ${esc(bin.bin_type)}</div>
            <div>สถานะค่าธรรมเนียม: ${esc(bin.subscriptions?.[0]?.status || 'ไม่มีรายการปีนี้')}</div>`;
    </script>
    @include('partials.bin_map._js')
@endsection
