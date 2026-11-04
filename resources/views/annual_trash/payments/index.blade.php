@extends('annual_trash.layout')

@section('title_page', 'รับชำระค่าจัดเก็บถังขยะรายปี')

@section('page-topic', 'รายละเอียดค่าถังขยะรายปี')
@section('nav-current', 'รับชำระค่าจัดเก็บถังขยะรายปี')
@section('nav-keptkayas.annual_payments.index', 'active')
@section('route-header')
 {{ route('keptkayas.annual_payments.index') }}
@endsection
@section('nav-main', 'รับชำระค่าจัดเก็บถังขยะรายปี')

@section('content')
    {{-- Section 1: Dashboard Cards (Summary) --}}
    <div class="row mb-4">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">ถังขยะรายปีทั้งหมด</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($totalBins) }}
                                    <span class="text-secondary text-sm font-weight-bolder">ถัง</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-dumpster text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">ชำระครบแล้ว</p>
                                <h5 class="font-weight-bolder mb-0 text-success">
                                    {{ number_format($paidBins) }}
                                    <span class="text-success text-sm font-weight-bolder">ถัง</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-check-circle text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">คงเหลือ / ค้างชำระ</p>
                                <h5 class="font-weight-bolder mb-0 text-danger">
                                    {{ number_format($pendingBins) }}
                                    <span class="text-danger text-sm font-weight-bolder">ถัง</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                <i class="fas fa-exclamation-circle text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Filter & Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                
                {{-- Header + Search Form --}}
                <div class="card-header pb-0">
                    <form action="{{ route('keptkayas.annual_payments.index') }}" method="GET">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <div class="mb-3 mb-md-0">
                                <h6 class="mb-0">รายการค่าถังขยะรายปี</h6>
                                <p class="text-xs text-secondary mb-0">ประจำปีงบประมาณ {{ $fiscalYear }}</p>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2">
                                {{-- Search Input --}}
                                <div class="input-group input-group-outline input-group-sm" style="width: 350px;">
                                    <label class="form-label">ค้นหา ชื่อ / รหัสถัง</label>
                                    <input type="text" name="search" class="form-control" value="{{ $search }}" onfocus="focused(this)" onfocusout="defocused(this)">
                                </div>

                                {{-- Status Filter --}}
                                <div class="input-group input-group-static input-group-sm" style="width: 140px;">
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>ทุกสถานะ</option>
                                        <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>ค้างชำระ</option>
                                        <option value="paid" {{ $statusFilter == 'paid' ? 'selected' : '' }}>ชำระแล้ว</option>
                                    </select>
                                </div>

                                {{-- Year Filter --}}
                                <div class="input-group input-group-static input-group-sm" style="width: 100px;">
                                    <select name="fy" class="form-control font-weight-bold text-primary" onchange="this.form.submit()">
                                        @foreach($availableFiscalYears as $year)
                                            <option value="{{ $year }}" {{ $year == $fiscalYear ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-icon btn-sm btn-primary mb-0">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2 mt-3">
                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                            <span class="alert-text text-white"><strong>สำเร็จ!</strong> {{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ผู้ใช้งาน</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">รายการถังขยะ / ยอดชำระ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                    @php
                                        $memberSubscriptions = $member->AnnualTrashs
                                            ->map(fn($bin) => $bin->subscriptions->first())
                                            ->filter()
                                            ->filter(function ($subscription) use ($statusFilter) {
                                                if ($statusFilter === 'paid') return $subscription->status === 'paid';
                                                if ($statusFilter === 'pending') return $subscription->status !== 'paid';
                                                return true;
                                            });
                                        $payableSubscriptions = $memberSubscriptions
                                            ->filter(fn($subscription) => (float) ($subscription->effective_annual_fee ?? $subscription->annual_fee) > (float) $subscription->total_paid_amt)
                                            ->values();
                                        $memberDueTotal = $payableSubscriptions->sum(fn($subscription) => max((float) ($subscription->effective_annual_fee ?? $subscription->annual_fee) - (float) $subscription->total_paid_amt, 0));
                                    @endphp
                                    <tr>
                                        <td class="align-top" style="width: 30%;">
                                            <div class="d-flex px-2 py-1">
                                                <div class="avatar avatar-sm me-3 bg-gradient-secondary rounded-circle">
                                                    <span class="text-white text-xs">{{ substr($member->firstname ?? 'U', 0, 1) }}</span>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $member->firstname ?? '-' }} {{ $member->lastname ?? '' }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $member->email ?? 'ไม่มีอีเมล' }}</p>
                                                    <p class="text-xs text-secondary mb-0"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($member->address ?? '-', 30) }}</p>
                                                    <p class="text-xs text-secondary mt-2 mb-2"><i class="fas fa-trash-alt me-1"></i>{{ $member->AnnualTrashs->count() }} ถัง</p>
                                                    @if($payableSubscriptions->isNotEmpty())
                                                        <button type="button" class="btn btn-sm btn-outline-primary mb-0 js-open-payment-modal"
                                                            data-subscription-ids="{{ $payableSubscriptions->pluck('id')->implode(',') }}"
                                                            data-bin-count="{{ $payableSubscriptions->count() }}"
                                                            data-amount="{{ number_format($memberDueTotal, 2, '.', '') }}"
                                                            data-target-name="ถังทั้งหมดของ {{ $member->firstname }} {{ $member->lastname }}">
                                                            <i class="fas fa-layer-group me-1"></i> จ่ายทั้งหมด {{ $payableSubscriptions->count() }} ถัง
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-top">
                                            @foreach($member->AnnualTrashs as $bin)
                                                {{-- Filter at View Level: แสดงเฉพาะถังที่มี Sub ของปีนี้ --}}
                                                @php
                                                    $sub = $bin->subscriptions->where('fiscal_year', $fiscalYear)->first();
                                                @endphp

                                                @if($sub)
                                                    @php
                                                        $effectiveAnnualFee = (float) ($sub->effective_annual_fee ?? $sub->annual_fee);
                                                        $isEffectivelyPaid = (float) $sub->total_paid_amt >= $effectiveAnnualFee;
                                                    @endphp
                                                    {{-- Filter Logic: ถ้าเลือกดู "จ่ายแล้ว" แต่ถังนี้ "ยังไม่จ่าย" ก็ข้ามไป (กรณี User มีหลายถัง) --}}
                                                    @if($statusFilter == 'paid' && !$isEffectivelyPaid) @continue @endif
                                                    @if($statusFilter == 'pending' && $isEffectivelyPaid) @continue @endif

                                                    <div class="card card-body border p-2 mb-2 shadow-none bg-gray-100">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="text-xs font-weight-bold mb-0 text-primary">
                                                                    <i class="fas fa-trash me-1"></i> {{ $bin->bin_code }}
                                                                </h6>
                                                                <span class="text-xxs text-secondary">{{ $bin->kpUserGroup->usergroup_name ?? 'ทั่วไป' }}</span>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="text-xs font-weight-bold d-block">ยอดตามรอบ: {{ number_format($effectiveAnnualFee, 2) }} ฿</span>
                                                                <span class="text-xxs text-secondary">ค้างชำระ: {{ number_format(max($effectiveAnnualFee - $sub->total_paid_amt, 0), 2) }} ฿</span>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                @if($isEffectivelyPaid)
                                                                    <span class="badge badge-sm bg-gradient-success">จ่ายครบแล้ว</span>
                                                                @else
                                                                    @if($sub->total_paid_amt > 0)
                                                                        <span class="badge badge-sm bg-gradient-warning">ค้างบางส่วน</span>
                                                                    @else
                                                                        <span class="badge badge-sm bg-gradient-danger">ค้างชำระ</span>
                                                                    @endif
                                                                    
                                                                    <button type="button" class="btn btn-sm btn-outline-primary mb-0 js-open-payment-modal"
                                                                        title="ชำระค่าถัง {{ $bin->bin_code }}"
                                                                        data-subscription-ids="{{ $sub->id }}"
                                                                        data-bin-count="1"
                                                                        data-amount="{{ number_format(max($effectiveAnnualFee - (float) $sub->total_paid_amt, 0), 2, '.', '') }}"
                                                                        data-target-name="ถัง {{ $bin->bin_code }}">
                                                                        <i class="fas fa-trash-alt me-1" aria-hidden="true"></i> ชำระถังนี้
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if($statusFilter !== 'paid')
                                                        <div class="card card-body border p-2 mb-2 shadow-none bg-gray-100">
                                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                                <div>
                                                                    <h6 class="text-xs font-weight-bold mb-0 text-primary"><i class="fas fa-trash me-1"></i> {{ $bin->bin_code }}</h6>
                                                                    <span class="text-xxs text-secondary">{{ $bin->kpUserGroup->usergroup_name ?? 'ทั่วไป' }} · ยังไม่มีรายการชำระปี {{ $fiscalYear }}</span>
                                                                </div>
                                                                <form method="POST" action="{{ route('keptkayas.annual_payments.create_subscription_for_bin', $bin) }}">
                                                                    @csrf
                                                                    <button class="btn btn-sm btn-outline-primary mb-0" type="submit">
                                                                        <i class="fas fa-file-invoice me-1" aria-hidden="true"></i> สร้างรายการชำระ
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center p-5">
                                            <div class="text-secondary mb-3">
                                                <i class="fas fa-search fa-3x opacity-5"></i>
                                            </div>
                                            <h6 class="text-secondary font-weight-normal">ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา</h6>
                                            @if($search || $statusFilter != 'all')
                                                <a href="{{ route('keptkayas.annual_payments.index', ['fy' => $fiscalYear]) }}" class="text-xs text-primary text-gradient font-weight-bold">
                                                    ล้างค่าการค้นหา
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $members->appends(['fy' => $fiscalYear, 'search' => $search, 'status' => $statusFilter])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg" id="paymentConfirmModal" tabindex="-1" aria-labelledby="paymentConfirmModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('keptkayas.annual_payments.bulk_payments') }}" id="bulkPaymentForm">
                    @csrf
                    <div id="selectedSubscriptions"></div>
                    <input type="hidden" name="fy" value="{{ $fiscalYear }}">
                    <div class="modal-header">
                        <div>
                            <h6 class="modal-title" id="paymentConfirmModalTitle">ยืนยันรับชำระ</h6>
                            <p class="text-sm text-secondary mb-0" id="paymentTargetName"></p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                    </div>
                    <div class="modal-body">

                    <div class="row">
                        <div class=" col-6 d-flex justify-content-between align-items-center border-bottom mb-3">
                            <span class="text-sm text-secondary">จำนวนถัง</span>
                            <strong id="paymentBinCount">0</strong>
                        </div>
                        <div class=" col-6 d-flex justify-content-between align-items-center mb-3">
                            <span class="text-sm text-secondary">ยอดค้างชำระรวม</span>
                            <strong class="text-primary" id="paymentAmount">0.00 บาท</strong>
                        </div>



                    </div>
                       

                        <div class="form-check form-switch border rounded-2 px-3 py-2 mb-3" id="includePreviousMonthsWrap" hidden>
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="include_previous_months" id="includePreviousMonths" value="1">
                            <label class="form-check-label" for="includePreviousMonths">
                                คิดเต็มปีงบประมาณ รวมเดือนก่อนสมัคร
                                <span class="d-block text-xs text-secondary">เปิดเพื่อเลือกเดือนย้อนหลังในทุกถังที่เลือก</span>
                            </label>
                        </div>
                        <div id="paymentMonthGroups" class="mb-3" aria-live="polite">
                            <p class="text-sm text-secondary mb-0">กำลังโหลดเดือนที่สามารถชำระ...</p>
                        </div>
                        <div class="alert alert-danger py-2 px-3 text-sm" id="paymentModalError" hidden></div>
                        @if(config('services.promptpay.id'))
                            <div class="border rounded-2 p-3 mb-3">
                                <button class="btn btn-outline-primary btn-sm mb-2" type="button" id="generatePromptPayQr" disabled>
                                    <i class="fas fa-qrcode me-1" aria-hidden="true"></i> สร้าง QR PromptPay
                                </button>
                                <div id="promptPayQrPanel" class="text-center" hidden>
                                    <img id="promptPayQrImage" alt="PromptPay QR สำหรับยอดที่เลือก" width="220" height="220">
                                    <p class="text-sm font-weight-bold mb-1" id="promptPayQrAmount"></p>
                                    <p class="text-xs text-warning mb-0">ตรวจสอบยอดเข้าบัญชีก่อนกดยืนยันรับชำระ</p>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning py-2 px-3 text-xs mb-3">ยังไม่ได้ตั้งค่า PROMPTPAY_ID จึงสร้าง QR รับเงินไม่ได้</div>
                        @endif
                        <div class="row">
                            
                        </div>
                        <div class="row">

                        <div class="mb-3 col-6">
                            <label class="form-label" for="bulkPaymentDate">วันที่รับชำระ</label>
                            <input class="form-control" type="date" id="bulkPaymentDate" name="payment_date" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label" for="bulkPaymentNotes">หมายเหตุ</label>
                            <input class="form-control" type="text" id="bulkPaymentNotes" name="notes" maxlength="255" placeholder="ไม่บังคับ">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary mb-0" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary mb-0"><i class="fas fa-check me-1"></i> ยืนยันบันทึกชำระ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const paymentModal = new bootstrap.Modal(document.getElementById('paymentConfirmModal'));
        const hiddenInputs = document.getElementById('selectedSubscriptions');
        const monthGroups = document.getElementById('paymentMonthGroups');
        const includePreviousMonths = document.getElementById('includePreviousMonths');
        const includePreviousMonthsWrap = document.getElementById('includePreviousMonthsWrap');
        const paymentAmount = document.getElementById('paymentAmount');
        const paymentModalError = document.getElementById('paymentModalError');
        const submitButton = document.querySelector('#bulkPaymentForm button[type="submit"]');
        const generateQrButton = document.getElementById('generatePromptPayQr');
        const qrPanel = document.getElementById('promptPayQrPanel');
        const qrImage = document.getElementById('promptPayQrImage');
        let currentGroups = [];

        function clearPromptPayQr() {
            if (!qrPanel) return;
            qrPanel.hidden = true;
            qrImage.removeAttribute('src');
        }

        function formatMoney(amount) {
            return Number(amount).toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function enforceMonthOrder(group) {
            let foundGap = false;
            const checkboxes = Array.from(group.querySelectorAll('.payment-month-checkbox'));

            checkboxes.forEach(checkbox => {
                const isPaid = checkbox.dataset.paid === 'true';
                const isBeforeMember = checkbox.dataset.beforeMember === 'true';

                if (isPaid) {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                    return;
                }

                if (isBeforeMember && !includePreviousMonths.checked) {
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

        function updatePaymentTotal() {
            let total = 0;
            monthGroups.querySelectorAll('.payment-bin-group').forEach(group => enforceMonthOrder(group));
            monthGroups.querySelectorAll('.payment-month-checkbox:checked:not(:disabled)').forEach(checkbox => total += Number(checkbox.dataset.amount || 0));
            paymentAmount.textContent = `${formatMoney(total)} บาท`;
            submitButton.disabled = total <= 0;
            if (generateQrButton) generateQrButton.disabled = total <= 0;
            clearPromptPayQr();

            monthGroups.querySelectorAll('.payment-bin-group').forEach(group => {
                const available = Array.from(group.querySelectorAll('.payment-month-checkbox:not(:disabled)'));
                const selectBin = group.querySelector('.select-bin-months');
                selectBin.checked = available.length > 0 && available.every(checkbox => checkbox.checked);
                selectBin.disabled = available.length === 0;
            });
        }

        function renderMonthGroups(groups) {
            currentGroups = groups;
            monthGroups.replaceChildren();
            let hasHistoryMonths = false;

            groups.forEach(group => {
                const section = document.createElement('section');
                section.className = 'payment-bin-group border rounded-2 p-2 mb-2';

                const header = document.createElement('div');
                header.className = 'd-flex justify-content-between align-items-center border-bottom pb-2 mb-2';
                const title = document.createElement('strong');
                title.className = 'text-sm';
                title.textContent = `ถัง ${group.bin_code}`;
                const selectWrap = document.createElement('label');
                selectWrap.className = 'form-check text-xs mb-0';
                const selectBin = document.createElement('input');
                selectBin.type = 'checkbox';
                selectBin.className = 'form-check-input select-bin-months';
                selectBin.setAttribute('aria-label', `เลือกเดือนทั้งหมดของถัง ${group.bin_code}`);
                selectWrap.append(selectBin, document.createTextNode(' เลือกทุกเดือน'));
                header.append(title, selectWrap);
                section.append(header);

                const grid = document.createElement('div');
                grid.className = 'row g-2';
                group.months.forEach(month => {
                    const column = document.createElement('div');
                    column.className = 'col-6 col-md-4';
                    const label = document.createElement('label');
                    label.className = 'form-check border rounded-2 p-2 d-flex gap-2 align-items-start h-100';
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.className = 'form-check-input payment-month-checkbox mt-1';
                    checkbox.name = `months[${group.subscription_id}][]`;
                    checkbox.value = month.value;
                    checkbox.dataset.amount = month.amount;
                    checkbox.dataset.beforeMember = String(month.before_member);
                    checkbox.dataset.paid = String(month.paid);
                    checkbox.checked = !month.paid && !month.before_member && Number(month.amount) > 0;
                    checkbox.disabled = month.paid || (month.before_member && !includePreviousMonths.checked);
                    if (month.before_member && !month.paid) hasHistoryMonths = true;

                    const text = document.createElement('span');
                    text.className = 'text-xs';
                    const monthTitle = document.createElement('strong');
                    monthTitle.className = 'd-block';
                    monthTitle.textContent = `${month.month_name} ${month.thai_year}`;
                    const monthAmount = document.createElement('span');
                    monthAmount.className = month.paid ? 'text-success' : 'text-secondary';
                    monthAmount.textContent = month.paid ? 'ชำระแล้ว' : `${formatMoney(month.amount)} บาท${month.before_member ? ' · ก่อนสมัคร' : ''}`;
                    text.append(monthTitle, monthAmount);
                    label.append(checkbox, text);
                    column.append(label);
                    grid.append(column);
                });
                section.append(grid);
                monthGroups.append(section);

                selectBin.addEventListener('change', () => {
                    section.querySelectorAll('.payment-month-checkbox').forEach(checkbox => {
                        const isPaid = checkbox.dataset.paid === 'true';
                        const isHistorical = checkbox.dataset.beforeMember === 'true';
                        if (!isPaid && (!isHistorical || includePreviousMonths.checked)) checkbox.checked = selectBin.checked;
                    });
                    updatePaymentTotal();
                });
            });

            includePreviousMonthsWrap.hidden = !hasHistoryMonths;
            monthGroups.querySelectorAll('.payment-month-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updatePaymentTotal);
            });
            updatePaymentTotal();
        }

        includePreviousMonths.addEventListener('change', () => {
            monthGroups.querySelectorAll('.payment-month-checkbox[data-before-member="true"]').forEach(checkbox => {
                const shouldEnable = includePreviousMonths.checked && checkbox.dataset.paid !== 'true';
                checkbox.disabled = !shouldEnable;
                checkbox.checked = shouldEnable && Number(checkbox.dataset.amount) > 0;
            });
            updatePaymentTotal();
        });

        if (generateQrButton) {
            generateQrButton.addEventListener('click', async () => {
                const formData = new FormData(document.getElementById('bulkPaymentForm'));
                generateQrButton.disabled = true;
                generateQrButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i> กำลังสร้าง QR';
                paymentModalError.hidden = true;

                try {
                    const response = await fetch("{{ route('keptkayas.annual_payments.payment_qr') }}", {
                        method: 'POST',
                        headers: { Accept: 'application/json' },
                        body: formData
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'สร้าง QR ไม่สำเร็จ');
                    qrImage.src = result.qr_data_uri;
                    document.getElementById('promptPayQrAmount').textContent = `${formatMoney(result.amount)} บาท`;
                    qrPanel.hidden = false;
                } catch (error) {
                    paymentModalError.textContent = error.message;
                    paymentModalError.hidden = false;
                } finally {
                    generateQrButton.disabled = false;
                    generateQrButton.innerHTML = '<i class="fas fa-qrcode me-1" aria-hidden="true"></i> สร้าง QR PromptPay';
                }
            });
        }

        document.querySelectorAll('.js-open-payment-modal').forEach(button => {
            button.addEventListener('click', async () => {
                const subscriptionIds = button.dataset.subscriptionIds.split(',').filter(Boolean);
                hiddenInputs.replaceChildren(...subscriptionIds.map(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'subscription_ids[]';
                    input.value = id;
                    return input;
                }));
                document.getElementById('paymentTargetName').textContent = button.dataset.targetName;
                document.getElementById('paymentBinCount').textContent = `${button.dataset.binCount} ถัง`;
                includePreviousMonths.checked = false;
                paymentModalError.hidden = true;
                monthGroups.replaceChildren(Object.assign(document.createElement('p'), {
                    className: 'text-sm text-secondary mb-0',
                    textContent: 'กำลังโหลดเดือนที่สามารถชำระ...'
                }));
                submitButton.disabled = true;
                paymentModal.show();

                try {
                    const query = new URLSearchParams();
                    subscriptionIds.forEach(id => query.append('subscription_ids[]', id));
                    const response = await fetch(`{{ route('keptkayas.annual_payments.payment_months') }}?${query}`, {
                        headers: { Accept: 'application/json' }
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'โหลดข้อมูลเดือนที่ชำระไม่สำเร็จ');
                    renderMonthGroups(result.groups);
                } catch (error) {
                    monthGroups.replaceChildren();
                    paymentModalError.textContent = error.message;
                    paymentModalError.hidden = false;
                }
            });
        });
    });
</script>
@endsection