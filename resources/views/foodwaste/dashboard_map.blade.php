@extends('layouts.foodwaste')

@section('title_page', 'แผนที่แสดงถังขยะเศษอาหาร')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">แผนที่ถังขยะเศษอาหาร</h6>
                    <p class="text-sm text-secondary mb-2">สีของถังตามเหตุที่สมาชิกแจ้งผ่าน LINE (กดสีด้านล่างเพื่อกรอง) · กดไอคอนถังเพื่อวางหลายจุดแล้วผูกกับสมาชิก</p>
                </div>
                <div class="card-body px-3 pt-0 pb-3">
                    @include('partials.bin_map._ui')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('partials.bin_map._js')
@endsection
