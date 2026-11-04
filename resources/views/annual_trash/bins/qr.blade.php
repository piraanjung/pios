@extends('annual_trash.layout')

@section('page-topic', 'QR Code ถังขยะ')
@section('content')
<div class="container py-4">
    <div class="card mx-auto text-center" style="max-width: 440px">
        <div class="card-header"><h6 class="mb-0">QR Code ถัง {{ $bin->bin_code }}</h6></div>
        <div class="card-body">
            <div class="mb-3">{!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(240)->margin(2)->generate($scanUrl) !!}</div>
            <p class="text-sm text-secondary">สแกนเพื่อเปิดแบบบันทึกผลการจัดเก็บ</p>
            <button class="btn btn-primary" type="button" onclick="window.print()">พิมพ์ป้าย QR</button>
        </div>
    </div>
</div>
<style>
    @media print { .navbar, .sidenav, .btn { display: none !important; } .card { border: 0; box-shadow: none; } }
</style>
@endsection