@extends('annual_trash.layout')

@section('nav-header', 'ขยะรายปี')
@section('page-topic', 'รายการใบแจ้งหนี้ค่าธรรมเนียมขยะรายปี')

@section('content')
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>ใบแจ้งหนี้ค้างชำระ (ปีงบประมาณ {{ $fiscalYear }})</h6>
            <small class="text-muted">รายการสมาชิกที่ยังชำระเงินไม่ครบตามกำหนด</small>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('keptkayas.annual_payments.print_selected_invoices') }}" method="POST" target="_blank">
            @csrf
            
            <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded-3">
                <div class="form-check ms-2">
                    <input type="checkbox" id="selectAllInvoices" class="form-check-input">
                    <label class="form-check-label text-sm font-weight-bold" for="selectAllInvoices">เลือกทั้งหมด</label>
                </div>
                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 mb-0">
                    <i class="fas fa-print me-1"></i> พิมพ์ใบแจ้งหนี้ที่เลือก
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 40px;">#</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">เลขสมาชิก</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อ - นามสกุล</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">จำนวนถังขยะ</th>
                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ค่าบริการรวม (บาท)</th>
                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ชำระแล้ว (บาท)</th>
                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pe-4">ยอดค้างชำระ (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoicesByUser as $userId => $userInvoices)
                            @php
                                $firstInvoice = $userInvoices->first();
                                $user = optional(optional($firstInvoice)->AnnualTrash)->user;
                                $annualMember = optional($user)->annualTrashMember;
                                $totalFee = $userInvoices->sum('annual_fee');
                                $totalPaid = $userInvoices->sum('total_paid_amt');
                                $totalDue = $totalFee - $totalPaid;
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    @foreach($userInvoices as $inv)
                                        <input type="checkbox" name="invoice_ids[]" value="{{ $inv->id }}" class="form-check-input invoice-checkbox d-none" checked>
                                    @endforeach
                                    <input type="checkbox" class="form-check-input user-group-checkbox" data-user-id="{{ $userId }}">
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border font-weight-bold">
                                        {{ $annualMember ? $annualMember->formatted_member_number : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm fw-bold">{{ $user ? $user->prefix.$user->firstname.' '.$user->lastname : 'ไม่พบชื่อ' }}</h6>
                                    <small class="text-muted text-xxs">{{ $user ? $user->address : '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $userInvoices->count() }} ใบ</span>
                                </td>
                                <td class="text-end text-sm font-weight-bold">{{ number_format($totalFee, 2) }}</td>
                                <td class="text-end text-sm text-success font-weight-bold">{{ number_format($totalPaid, 2) }}</td>
                                <td class="text-end text-sm text-danger font-weight-bold pe-4">{{ number_format($totalDue, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-secondary">ไม่พบรายการใบแจ้งหนี้ค้างชำระ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('selectAllInvoices')?.addEventListener('change', function() {
        document.querySelectorAll('.user-group-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection