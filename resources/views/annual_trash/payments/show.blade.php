@extends('annual_trash.layout')

@section('page-topic', 'รายละเอียดค่าถังขยะรายปี')
@section('nav-header', 'รับชำระค่าจัดเก็บถังขยะรายปี')
@section('route-header')
 {{ route('keptkayas.annual_payments.index') }}
@endsection
@section('nav-main', 'ชำระเงิน')
    
@section('style')
    <style>
        .month-item {
            transition: all 0.2s ease;
        }
        
        /* 1. จ่ายแล้ว (Paid) - สีเขียว */
        .month-item.paid {
            opacity: 0.9;
            background-color: #f0fdf4; 
            border-color: #bbf7d0 !important;
        }
        .month-item.paid label {
            cursor: default;
            color: #166534 !important;
            font-weight: bold;
        }

        /* 2. ก่อนเป็นสมาชิก (Before Member) - สีเทาเข้ม */
        .month-item.before-member {
            opacity: 0.6;
            background-color: #e9ecef; 
            border-color: #dee2e6 !important;
        }
        .month-item.before-member label {
            cursor: not-allowed;
            color: #6c757d !important;
        }

        .summary-value { font-weight: 600; color: #344767; }
        .total-amount-display { font-size: 1.5rem; font-weight: 700; color: #cb0c9f; }
        .fiscal-month-card { min-height: 82px; }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 mx-auto">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="mb-0 font-weight-bold">รายละเอียดการชำระเงิน</h6>
                    <p class="text-xs text-secondary mb-0">จัดการข้อมูลและบันทึกการรับเงิน</p>
                </div>
                <a href="{{ route('keptkayas.annual_payments.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                    <i class="fas fa-arrow-left me-2"></i> กลับหน้ารายการ
                </a>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                    <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                    <span class="alert-text"><strong>สำเร็จ!</strong> {{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif

            <div class="row">
                {{-- Left Column (User Info) --}}
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">ข้อมูลสมาชิก</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-4">
                                <div class="avatar avatar-lg me-3 bg-gradient-primary border-radius-lg shadow">
                                    <i class="fas fa-user fa-lg text-white"></i>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">{{ $wasteBinSubscription->AnnualTrash->user->firstname ?? '-' }} {{ $wasteBinSubscription->AnnualTrash->user->lastname ?? '' }}</h6>
                                    <p class="text-xs text-secondary mb-0">
                                        <i class="fas fa-trash-alt me-1"></i> 
                                        {{ $wasteBinSubscription->AnnualTrash->bin_code ?? 'ไม่ระบุรหัสถัง' }}
                                    </p>
                                </div>
                            </div>
                            <hr class="horizontal dark my-3">
                            <ul class="list-group">
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center"><i class="fas fa-calendar-alt text-white opacity-10"></i></div>
                                        <div class="d-flex flex-column"><h6 class="mb-1 text-dark text-sm">ปีงบประมาณ</h6></div>
                                    </div>
                                    <div class="d-flex align-items-center text-dark text-sm font-weight-bold">{{ $wasteBinSubscription->fiscal_year  }}</div>
                                </li>
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-info shadow text-center"><i class="fas fa-coins text-white opacity-10"></i></div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">ค่าธรรมเนียมรายปี</h6>
                                            <span class="text-xs">เดือนละ {{ number_format($wasteBinSubscription->month_fee, 0) }} ฿</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center text-dark text-sm font-weight-bold"><span id="annual_fee_display">{{ number_format($proratedAnnualFee, 2) }}</span> ฿</div>
                                </li>
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-success shadow text-center"><i class="fas fa-check text-white opacity-10"></i></div>
                                        <div class="d-flex flex-column"><h6 class="mb-1 text-dark text-sm">ชำระแล้ว</h6></div>
                                    </div>
                                    <div class="d-flex align-items-center text-success text-sm font-weight-bold">{{ number_format($wasteBinSubscription->total_paid_amt, 2) }} ฿</div>
                                </li>
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-danger shadow text-center"><i class="fas fa-exclamation text-white opacity-10"></i></div>
                                        <div class="d-flex flex-column"><h6 class="mb-1 text-dark text-sm">ค้างชำระ</h6></div>
                                    </div>
                                    <div class="d-flex align-items-center text-danger text-sm font-weight-bold"><span id="outstanding_display">{{ number_format(max($proratedAnnualFee - $wasteBinSubscription->total_paid_amt, 0), 2) }}</span> ฿</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Right Column (Payment Form) --}}
                <div class="col-lg-8">
                    <div class="card h-100">
                        @if($isBinActiveForAnnualCollection)
                            <div class="card-header pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">เลือกเดือนที่ต้องการชำระ</h6>
                                        <p class="text-xs text-secondary mb-0">ปีงบประมาณ ต.ค. {{ $wasteBinSubscription->fiscal_year - 1 }} – ก.ย. {{ $wasteBinSubscription->fiscal_year }}</p>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllMonths">
                                        <label class="form-check-label font-weight-bold" for="selectAllMonths">เลือกชำระทั้งหมด</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="paymentForm" action="{{ route('keptkayas.annual_payments.store_payment', $wasteBinSubscription->id) }}" method="POST"
                                    data-total-paid="{{ (float) $wasteBinSubscription->total_paid_amt }}"
                                    data-prorated-annual-fee="{{ $proratedAnnualFee }}"
                                    data-full-year-annual-fee="{{ $fullYearAnnualFee }}">
                                    @csrf
                                    <input type="hidden" name="include_previous_months" id="includePreviousMonthsValue" value="{{ old('include_previous_months') ? '1' : '0' }}">

                                    @if($hasHistoricalMonths)
                                        <div class="form-check form-switch border rounded-2 px-3 py-2 mb-3">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" id="includePreviousMonths" value="1" {{ old('include_previous_months') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="includePreviousMonths">
                                                คิดเต็มปีงบประมาณ รวมเดือนก่อนสมัคร
                                                <span class="d-block text-xs text-secondary">เปิดเพื่อเลือกเดือนย้อนหลังได้ เจ้าหน้าที่เป็นผู้ยืนยันรูปแบบคิดค่าธรรมเนียม</span>
                                            </label>
                                        </div>
                                    @endif
                                    
                                    {{-- Month Grid --}}
                                    <div class="row g-2 mb-4">
                                        @foreach($paymentSchedule as $monthData)
                                            @php
                                                $checkboxId = 'month_' . $monthData['month_num'] . '_' . $monthData['year'];
                                                $isBeforeMember = $monthData['before_member'];
                                                $isPaid = $monthData['is_paid'];
                                                $isServerDisabled = $isPaid || ($isBeforeMember && !old('include_previous_months'));
                                                $oldSelection = old('selected_months');
                                                $isChecked = $isPaid
                                                    || ($oldSelection !== null
                                                        ? in_array($monthData['month_num'] . '|' . $monthData['year'], $oldSelection)
                                                        : (!$isBeforeMember && $monthData['due_amount'] > 0));
                                                $itemClass = $isPaid ? 'paid' : ($isBeforeMember ? 'before-member' : '');

                                                $monthNameShort = \Carbon\Carbon::createFromDate(null, $monthData['month_num'], 1)->locale('th')->isoFormat('MMM');
                                                $thaiYear = $monthData['year'] + 543;
                                            @endphp

                                            <div class="col-6 col-sm-4 col-md-3">
                                                <div class="form-check p-2 border rounded-2 month-item fiscal-month-card {{ $itemClass }}">
                                                    <input class="form-check-input month-checkbox ms-1" type="checkbox"
                                                        id="{{ $checkboxId }}"
                                                        name="selected_months[]"
                                                        value="{{ $monthData['month_num'] . '|' . $monthData['year'] }}"
                                                        data-due-amount="{{ $monthData['due_amount'] }}"
                                                        data-before-member="{{ $isBeforeMember ? 'true' : 'false' }}"
                                                        data-paid="{{ $isPaid ? 'true' : 'false' }}"
                                                        {{ $isServerDisabled ? 'disabled' : '' }}
                                                        {{ $isChecked ? 'checked' : '' }}
                                                    >
                                                    
                                                    <label class="form-check-label ms-2 w-75" for="{{ $checkboxId }}">
                                                        <span class="d-block text-sm font-weight-bold">
                                                            {{ $monthNameShort }} {{ $thaiYear }}
                                                        </span>

                                                        @if($isPaid)
                                                            <span class="text-xs text-success"><i class="fas fa-check-circle me-1"></i>จ่ายแล้ว</span>
                                                        @elseif($isBeforeMember)
                                                            <span class="text-xs text-secondary">ก่อนสมัคร · {{ number_format($monthData['due_amount'], 2) }} ฿</span>
                                                        @else
                                                            <span class="text-xs text-muted">คงเหลือ {{ number_format($monthData['due_amount'], 2) }} ฿</span>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <hr class="horizontal dark my-4">
                                    
                                    {{-- Payment Inputs --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label for="payment_date" class="font-weight-bold">วันที่รับชำระ</label>
                                                <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="input-group input-group-static">
                                                <label for="notes">หมายเหตุ (ถ้ามี)</label>
                                                <input type="text" class="form-control" id="notes" name="notes" placeholder="ระบุเลขที่ใบเสร็จเล่มอื่น หรือบันทึกเพิ่มเติม">
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-flex flex-column align-items-end justify-content-center bg-gray-100 border-radius-lg p-3">
                                            <span class="text-sm text-secondary font-weight-bold mb-2">ยอดเงินที่ต้องชำระรวม</span>
                                            <div class="d-flex align-items-baseline">
                                                <span class="h4 text-primary font-weight-bolder me-2" id="display_amount">0.00</span>
                                                <span class="text-sm text-secondary">บาท</span>
                                            </div>
                                            <input type="hidden" id="amount_paid" name="amount_paid" value="0.00">
                                            <input type="hidden" name="amount_paid_from_js_calc" id="amount_paid_from_js_calc" value="0.00">
                                            <button type="submit" class="btn bg-gradient-primary w-100 mt-3 shadow-lg">
                                                <i class="fas fa-save me-2"></i> บันทึกการรับเงิน
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        @else
                            {{-- กรณีบริการถูกระงับ --}}
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center py-5">
                                <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mb-3">
                                    <i class="fas fa-ban opacity-10" aria-hidden="true"></i>
                                </div>
                                <h5 class="font-weight-bolder">บริการนี้ถูกระงับชั่วคราว</h5>
                                <p class="mb-0 text-sm text-secondary">ถังขยะนี้ไม่ได้เปิดใช้งานบริการรายปี จึงไม่สามารถรับชำระเงินได้</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = Array.from(document.querySelectorAll('.month-checkbox'));
        const selectAllCheckbox = document.getElementById('selectAllMonths');
        const includePreviousMonths = document.getElementById('includePreviousMonths');
        const includePreviousMonthsValue = document.getElementById('includePreviousMonthsValue');
        const paymentForm = document.getElementById('paymentForm');
        const amountPaidInput = document.getElementById('amount_paid');
        const amountPaidFromJsCalcInput = document.getElementById('amount_paid_from_js_calc');
        const displayAmount = document.getElementById('display_amount');
        const annualFeeDisplay = document.getElementById('annual_fee_display');
        const outstandingDisplay = document.getElementById('outstanding_display');
        const totalPaid = Number(paymentForm.dataset.totalPaid);
        const proratedAnnualFee = Number(paymentForm.dataset.proratedAnnualFee);
        const fullYearAnnualFee = Number(paymentForm.dataset.fullYearAnnualFee);

        function updateHistoricalMonthAccess(selectHistoricalMonths = false) {
            const includeHistorical = Boolean(includePreviousMonths?.checked);
            includePreviousMonthsValue.value = includeHistorical ? '1' : '0';

            checkboxes.forEach(checkbox => {
                const isPaid = checkbox.dataset.paid === 'true';
                const isBeforeMember = checkbox.dataset.beforeMember === 'true';
                const monthCard = checkbox.closest('.month-item');

                if (isPaid) {
                    checkbox.disabled = true;
                    checkbox.checked = true;
                } else if (isBeforeMember) {
                    checkbox.disabled = !includeHistorical;
                    if (selectHistoricalMonths) checkbox.checked = includeHistorical;
                    if (!includeHistorical) checkbox.checked = false;
                } else {
                    checkbox.disabled = false;
                }

                monthCard.classList.toggle('before-member', isBeforeMember && !includeHistorical && !isPaid);
            });

            enforceMonthOrder();
        }

        function enforceMonthOrder() {
            let foundGap = false;

            checkboxes.forEach(checkbox => {
                const isPaid = checkbox.dataset.paid === 'true';
                const isBeforeMember = checkbox.dataset.beforeMember === 'true';

                if (isPaid) {
                    checkbox.disabled = true;
                    checkbox.checked = true;
                    return;
                }

                if (isBeforeMember && !includePreviousMonths?.checked) {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                    return;
                }

                if (foundGap) {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                    return;
                }

                checkbox.disabled = false;
                if (!checkbox.checked) foundGap = true;
            });
        }

        function updateAmountPaid() {
            enforceMonthOrder();
            let totalAmount = 0;
            let hasPayable = false;
            let allPayableChecked = true;

            checkboxes.forEach(cb => {
                const isPaid = cb.dataset.paid === 'true';
                if (!isPaid && !cb.disabled && cb.checked) {
                    totalAmount += parseFloat(cb.dataset.dueAmount);
                }

                if (!cb.disabled && !isPaid) {
                    hasPayable = true;
                    if (!cb.checked) allPayableChecked = false;
                }
            });

            // Update UI Select All
            if (selectAllCheckbox) {
                if (!hasPayable) {
                    selectAllCheckbox.disabled = true;
                    selectAllCheckbox.checked = true; 
                } else {
                    selectAllCheckbox.disabled = false;
                    selectAllCheckbox.checked = allPayableChecked;
                }
            }

            const formattedTotal = totalAmount.toFixed(2);
            amountPaidInput.value = formattedTotal;
            amountPaidFromJsCalcInput.value = formattedTotal;
            const formatMoney = amount => new Intl.NumberFormat('th-TH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
            const targetAnnualFee = includePreviousMonths?.checked ? fullYearAnnualFee : proratedAnnualFee;

            if (displayAmount) {
                displayAmount.innerText = new Intl.NumberFormat('th-TH', { 
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 2 
                }).format(totalAmount);
            }
            if (annualFeeDisplay) annualFeeDisplay.textContent = formatMoney(targetAnnualFee);
            if (outstandingDisplay) outstandingDisplay.textContent = formatMoney(Math.max(targetAnnualFee - totalPaid, 0));
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateAmountPaid);
        });

        if (includePreviousMonths) {
            includePreviousMonths.addEventListener('change', () => {
                updateHistoricalMonthAccess(includePreviousMonths.checked);
                updateAmountPaid();
            });
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    const isBeforeMember = cb.dataset.beforeMember === 'true';
                    if (cb.dataset.paid !== 'true' && (!isBeforeMember || includePreviousMonths?.checked)) {
                        cb.checked = this.checked;
                    }
                });
                updateAmountPaid();
            });
        }

        updateHistoricalMonthAccess();
        updateAmountPaid();

    });
</script>
@endsection