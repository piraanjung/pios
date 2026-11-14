@extends('layouts.tabwater_staff_mobile')

@section('route-header', 'ระบบประปา')
@section('route-main', 'จดมิเตอร์ภาคสนาม')
@section('route-back', route('tabwater.staff.mobile.index'))

@section('content')
@php
    $m = $meterInfo ?? ($meter[0] ?? null);
    $lastRecording = floatval($m->last_meter_recording ?? 0);
    $customerName = trim(($m->user->prefix ?? '') . ($m->user->firstname ?? '') . ' ' . ($m->user->lastname ?? ''));
    if (empty($customerName)) {
        $customerName = 'ผู้ใช้น้ำ (รหัสมิเตอร์ #' . ($m->meter_id ?? '') . ')';
    }
@endphp

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

        <!-- 1. Customer & Meter Info Card -->
        <div class="card border-0 shadow-sm border-radius-xl mb-4 overflow-hidden">
            <div class="card-header bg-gradient-primary p-3 text-white d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="icon icon-shape icon-sm bg-white text-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-white mb-0 font-weight-bold">{{ $customerName }}</h6>
                        <small class="text-white opacity-8">เลขที่มิเตอร์: {{ $m->meter_id ?? '-' }}</small>
                    </div>
                </div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 font-weight-bold">
                    <i class="fas fa-tint me-1"></i> ใช้งานปกติ
                </span>
            </div>

            <div class="card-body p-3 bg-white">
                <div class="row g-2 text-sm">
                    <div class="col-6">
                        <span class="text-muted small">ที่อยู่/สถานที่ติดตั้ง:</span>
                        <p class="font-weight-bold text-dark mb-0">{{ $m->meter_address ?? ($m->user->address ?? 'ตามฐานข้อมูลผู้ใช้น้ำ') }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted small">โซน/สายส่งน้ำ:</span>
                        <p class="font-weight-bold text-dark mb-0">{{ $m->undertake_zone->zone_name ?? 'โซนหลัก' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Meter Reading Input Form -->
        <div class="card border-0 shadow-sm border-radius-xl p-4 bg-white">
            <h5 class="font-weight-bolder text-dark mb-3">
                <i class="fas fa-tachometer-alt text-primary me-2"></i> บันทึกเลขมาตรวัดน้ำ
            </h5>

            <form id="meterForm" action="{{ route('tabwater.staff.mobile.store') }}" method="POST">
                @csrf
                <input type="hidden" name="meter_id" value="{{ $m->meter_id ?? '' }}">

                <!-- Previous Reading (Read-only) -->
                <div class="mb-3">
                    <label class="form-label text-secondary font-weight-bold d-flex justify-content-between">
                        <span><i class="fas fa-history me-1 text-secondary"></i> เลขมิเตอร์ครั้งก่อน</span>
                        <span class="badge bg-light text-secondary rounded-pill px-2">อ่านอย่างเดียว</span>
                    </label>
                    <div class="input-group">
                        <input type="text" id="last_meter_recording" class="form-control form-control-lg bg-light text-dark font-weight-bolder text-center fs-4 border-0"
                            readonly value="{{ $lastRecording }}">
                        <span class="input-group-text bg-light border-0 text-secondary font-weight-bold">หน่วย</span>
                    </div>
                </div>

                <!-- Current Reading (Large Touch Input) -->
                <div class="mb-3">
                    <label for="currentmeter" class="form-label text-dark font-weight-bolder fs-6">
                        <i class="fas fa-pencil-alt text-primary me-1"></i> เลขมิเตอร์ครั้งนี้ <span class="text-danger">*</span>
                    </label>
                    <div class="input-group input-group-lg shadow-sm border-radius-lg overflow-hidden">
                        <input type="number" step="any" inputmode="numeric" pattern="[0-9]*" name="currentmeter" id="currentmeter"
                            class="form-control form-control-lg text-center font-weight-bolder text-primary fs-2 py-3"
                            placeholder="กรอกเลขมิเตอร์" required autofocus autocomplete="off"
                            style="border: 2px solid #2152ff; letter-spacing: 2px;">
                        <span class="input-group-text bg-primary text-white font-weight-bold px-3">หน่วย</span>
                    </div>
                    <div class="form-text text-muted small mt-1">
                        พิมพ์ตัวเลขมาตรวัดน้ำปัจจุบันที่อ่านได้จากหน้าปัดมิเตอร์
                    </div>
                </div>

                <!-- Live Consumption Calculation Display -->
                <div id="consumptionBox" class="alert alert-secondary border-0 p-3 my-3 border-radius-lg d-flex align-items-center justify-content-between">
                    <div>
                        <span class="d-block small text-secondary font-weight-bold">ปริมาณน้ำที่ใช้ในงวดนี้:</span>
                        <h4 class="mb-0 font-weight-bolder text-dark" id="unitsDisplay">0.00 หน่วย</h4>
                    </div>
                    <div id="statusBadge">
                        <span class="badge bg-secondary rounded-pill px-3 py-2">รอระบุตัวเลข</span>
                    </div>
                </div>

                <!-- Validation Alert -->
                <div id="errorAlert" class="alert alert-danger text-white border-0 py-2 px-3 mb-3 d-none border-radius-lg">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    <strong>ข้อผิดพลาด:</strong> เลขมิเตอร์ครั้งนี้ต้องไม่น้อยกว่าเลขครั้งก่อน (<span id="prevValText">{{ $lastRecording }}</span>)
                </div>

                <!-- Submit Button -->
                <button type="submit" id="btnSubmit" class="btn btn-lg bg-gradient-success w-100 rounded-pill py-3 font-weight-bold fs-5 shadow-sm mb-0">
                    <i class="fas fa-save me-2"></i> บันทึกข้อมูลเลขมิเตอร์
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        const lastVal = parseFloat($('#last_meter_recording').val()) || 0;
        const currentInput = $('#currentmeter');
        const unitsDisplay = $('#unitsDisplay');
        const statusBadge = $('#statusBadge');
        const errorAlert = $('#errorAlert');
        const btnSubmit = $('#btnSubmit');
        const consumptionBox = $('#consumptionBox');

        currentInput.on('input keyup change', function () {
            const rawVal = $(this).val();

            if (rawVal === '' || isNaN(rawVal)) {
                unitsDisplay.text('0.00 หน่วย').removeClass('text-success text-danger').addClass('text-dark');
                statusBadge.html('<span class="badge bg-secondary rounded-pill px-3 py-2">รอระบุตัวเลข</span>');
                errorAlert.addClass('d-none');
                btnSubmit.prop('disabled', false);
                consumptionBox.removeClass('alert-success alert-danger').addClass('alert-secondary');
                return;
            }

            const currentVal = parseFloat(rawVal);
            const diff = currentVal - lastVal;

            if (diff < 0) {
                // Invalid: Current < Last
                unitsDisplay.text(diff.toFixed(2) + ' หน่วย').removeClass('text-success text-dark').addClass('text-danger');
                statusBadge.html('<span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-times me-1"></i> ค่าน้อยกว่าครั้งก่อน</span>');
                errorAlert.removeClass('d-none');
                btnSubmit.prop('disabled', true);
                consumptionBox.removeClass('alert-secondary alert-success').addClass('alert-danger');
            } else {
                // Valid
                unitsDisplay.text('+' + diff.toFixed(2) + ' หน่วย').removeClass('text-danger text-dark').addClass('text-success');
                statusBadge.html('<span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i> ปกติ</span>');
                errorAlert.addClass('d-none');
                btnSubmit.prop('disabled', false);
                consumptionBox.removeClass('alert-secondary alert-danger').addClass('alert-success');
            }
        });

        $('#meterForm').on('submit', function (e) {
            btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span> กำลังบันทึก...');
        });
    });
</script>
@endsection
