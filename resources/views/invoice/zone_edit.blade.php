@extends('layouts.admin1')

@section('nav-invoice') active @endsection
@section('nav-header') จัดการใบแจ้งหนี้ @endsection
@section('nav-main')
    <a href="{{ route('invoice.index') }}"> ออกใบแจ้งหนี้</a>
@endsection
@section('nav-current') แก้ไขข้อมูลใบแจ้งหนี้ @endsection
@section('nav-topic')
    เส้นทาง: {{ $subzone->subzone_name ?? ($inv_in_seleted_subzone[0]->undertake_subzone->subzone_name ?? '-') }}
@endsection

@section('style')
    <style>
        .hidden {
            display: none;
        }

        .input-error {
            color: red !important;
            font-weight: bold;
            border: 2px solid red !important;
        }

        .input-warning {
            background-color: #fff3cd !important;
        }

        .bg-readonly {
            background-color: #e9ecef;
        }

        /* สไตล์สำหรับการ์ดและระบบ Filter (เหมือนหน้า zone_create) */
        .water-card {
            transition: all 0.3s ease;
        }

        .water-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .water-card.card-edited {
            border-top: 3px solid #28a745 !important;
            box-shadow: 0 0 12px rgba(40, 167, 69, 0.2);
        }

        .row>* {
            padding-right: 5px !important;
            padding-left: 5px !important;
            padding-bottom: 4px;
        }

        .form-floating input {
            font-size: 1.2rem;
        }
    </style>
    <script src="{{ asset('/adminlte/plugins/jquery/jquery.min.js') }}"></script>
@endsection

@section('content')
    <div id="web_app" class="">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('invoice.zone_update') }}" method="POST" id="invoiceEditForm">
                    @csrf
                    <input type="hidden" name="subzone_id" value="{{ $subzone_id }}">
                    <input type="hidden" name="curr_inv_prd" value="{{ $curr_inv_prd ?? '' }}">

                    <!-- Toolbar & Filter Search Bar -->
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <input type="submit" class="btn btn-primary btn-block" id="print_multi_inv"
                                value="บันทึกการแก้ไขข้อมูลทั้งหมด">
                        </div>

                        <!-- Filter Search Input -->
                        <div class="col-md-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="cardFilterInput" class="form-control"
                                    placeholder="ค้นหาตามเลขใบแจ้งหนี้, เลขมิเตอร์, Factory No., ชื่อ-สกุล หรือบ้านเลขที่...">
                            </div>
                        </div>
                    </div>

                    <!-- Card Container -->
                    <div class="row" id="cardContainer">
                        <?php $i = 1; ?>
                        @foreach ($inv_in_seleted_subzone as $u_meter_info)
                            @php
                                $inv = $u_meter_info->invoice_temp->first();
                                if (!$inv) {
                                    continue;
                                }

                                $rateConfig = $u_meter_info->meter_type?->rateConfigs?->first() ?? null;
                                $pricingTypeId = $rateConfig->pricing_type_id ?? 1;
                                $pricePerUnit = $rateConfig->fixed_rate_per_unit ?? 0;
                                $minUsageCharge = $rateConfig->min_usage_charge ?? 0;
                                $chargeMinOnlyZeroUse = (int) ($rateConfig->charge_min_only_zero_use ?? 0);
                                $vatRate = $rateConfig->vat ?? 0;
                                $tiersJson = ($rateConfig && $rateConfig->Ratetiers)
                                    ? $rateConfig->Ratetiers->sortBy('tier_order')->values()->toJson()
                                    : '[]';

                                $fullName = trim(($u_meter_info->user->firstname ?? '-') . ' ' . ($u_meter_info->user->lastname ?? '') . ' ' . ($u_meter_info->submeter_name ?? ''));
                                $address = $u_meter_info->meter_address ?? ($u_meter_info->user->address ?? '-');
                                $memberCode = 'U' . str_pad((string) $u_meter_info->user_id, 4, '0', STR_PAD_LEFT);
                                $recorderName = trim(($inv->recorder->firstname ?? '') . ' ' . ($inv->recorder->lastname ?? ''));

                                $searchKeyword = strtolower(
                                    ($inv->id ?? '') . ' ' .
                                    ($u_meter_info->meternumber ?? '') . ' ' .
                                    ($u_meter_info->factory_no ?? '') . ' ' .
                                    $memberCode . ' ' .
                                    $fullName . ' ' .
                                    $address
                                );
                            @endphp

                            <div class="col-xl-4 col-md-6 mb-4 filter-item" data-search="{{ $searchKeyword }}">
                                <div class="card water-card h-100 border-top border-warning" id="card_box_{{ $i }}">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                        <div>
                                            <span class="badge badge-warning text-dark" id="badge_status_{{ $i }}">
                                                ลำดับที่ {{ $i }} | ใบแจ้งหนี้ #{{ $inv->id }}
                                            </span>
                                            <span class="badge badge-info ml-1">{{ $inv->status }}</span>
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-outline-danger"
                                            onclick="del('{{ $inv->id }}')">
                                            <i class="fas fa-trash"></i> ลบ
                                        </a>
                                    </div>

                                    <div class="card-body">
                                        <h5 class="card-title font-weight-bold text-dark mb-1">
                                            <span class="username text-primary" style="cursor: pointer;"
                                                data-user_id="{{ $u_meter_info->user_id ?? 0 }}">
                                                <i class="fas fa-search-plus"></i> {{ $fullName }}
                                            </span>
                                            <small class="text-muted font-weight-normal">({{ $memberCode }})</small>
                                        </h5>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="fas fa-map-marker-alt text-danger"></i> บ้านเลขที่:
                                            {{ $address }}<br>
                                            <i class="fas fa-tachometer-alt text-success"></i> เลขมิเตอร์:
                                            <b>{{ $u_meter_info->meternumber ?? '-' }}</b> | Factory No:
                                            <b>{{ $u_meter_info->factory_no ?? '-' }}</b>
                                        </p>

                                        <!-- Hidden Inputs -->
                                        <input type="hidden" value="{{ $inv->id }}" name="data[{{ $i }}][inv_id]"
                                            id="inv_id{{ $i }}">
                                        <input type="hidden" value="0" id="changevalue{{ $i }}"
                                            name="data[{{ $i }}][changevalue]" class="changevalue-input">
                                        <input type="hidden" value="{{ $u_meter_info->meternumber }}"
                                            name="data[{{ $i }}][meternumber]" id="meternumber{{ $i }}">
                                        <input type="hidden" value="{{ $u_meter_info->meter_id }}"
                                            name="data[{{ $i }}][meter_id]">
                                        <input type="hidden" value="{{ $u_meter_info->user_id }}"
                                            name="data[{{ $i }}][user_id]">
                                        <input type="hidden" value="{{ $address }}"
                                            name="data[{{ $i }}][address]">
                                        <input type="hidden" value="{{ $pricePerUnit }}"
                                            name="data[{{ $i }}][fixed_rate_per_unit]">

                                        <hr class="my-2">

                                        <!-- Form Inputs (เหมือน zone_create) -->
                                        <div class="row">
                                            <div class="form-floating col-6">
                                                <input type="number" step="any" value="{{ $inv->lastmeter }}"
                                                    name="data[{{ $i }}][lastmeter]" data-id="{{ $i }}"
                                                    id="lastmeter{{ $i }}"
                                                    class="form-control form-control-sm text-end lastmeter"
                                                    data-original="{{ $inv->lastmeter }}">
                                                <label class="small text-muted mb-1">ยกยอดมา</label>
                                            </div>
                                            <div class="form-floating col-6">
                                                <input type="number" step="any"
                                                    value="{{ $inv->currentmeter ?? 0 }}"
                                                    name="data[{{ $i }}][currentmeter]" data-id="{{ $i }}"
                                                    id="currentmeter{{ $i }}"
                                                    data-pricing-type="{{ $pricingTypeId }}"
                                                    data-price="{{ $pricePerUnit }}"
                                                    data-tiers='{{ $tiersJson }}'
                                                    data-vat="{{ $vatRate }}"
                                                    data-reserve="{{ $minUsageCharge }}"
                                                    data-charge-min-only-zero="{{ $chargeMinOnlyZeroUse }}"
                                                    class="form-control form-control-sm text-end currentmeter border-success"
                                                    data-original="{{ $inv->currentmeter ?? 0 }}">
                                                <label class="small text-muted mb-1">มิเตอร์ปัจจุบัน</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-floating col-6">
                                                <input type="text" readonly
                                                    class="form-control form-control-sm text-end water_used_net bg-readonly font-weight-bold"
                                                    id="water_used_net{{ $i }}"
                                                    name="data[{{ $i }}][water_used]"
                                                    value="{{ $inv->water_used ?? 0 }}">
                                                <label class="small text-muted mb-1">ใช้น้ำ (หน่วย)</label>
                                            </div>
                                            <div class="form-floating col-6">
                                                <input type="text" readonly
                                                    class="form-control form-control-sm text-end paid bg-readonly"
                                                    name="data[{{ $i }}][paid]" id="paid{{ $i }}"
                                                    value="{{ number_format((float) ($inv->paid ?? 0), 2, '.', '') }}">
                                                <label class="small text-muted mb-1">เป็นเงิน (บาท)</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-floating col-5">
                                                <input type="text" readonly
                                                    name="data[{{ $i }}][reserve_meter]"
                                                    class="form-control form-control-sm text-end meter_reserve_price bg-readonly"
                                                    id="meter_reserve_price{{ $i }}"
                                                    value="{{ number_format((float) ($inv->reserve_meter ?? 0), 2, '.', '') }}">
                                                <label class="small text-muted mb-1">ค่ารักษามาตร</label>
                                            </div>
                                            <div class="form-floating col-3">
                                                <input type="text" readonly
                                                    name="data[{{ $i }}][vat]"
                                                    class="form-control form-control-sm text-end vat bg-readonly"
                                                    data-id="{{ $i }}" id="vat{{ $i }}"
                                                    value="{{ number_format((float) ($inv->vat ?? 0), 2, '.', '') }}">
                                                <label class="small text-muted mb-1">ภาษี 7%</label>
                                            </div>
                                            <div class="form-floating col-4">
                                                <input type="text" readonly
                                                    class="form-control form-control-sm text-end total bg-readonly font-weight-bold text-danger"
                                                    name="data[{{ $i }}][totalpaid]" id="total{{ $i }}"
                                                    value="{{ number_format((float) ($inv->totalpaid ?? 0), 2, '.', '') }}">
                                                <label class="small text-muted mb-1 text-danger font-weight-bold">รวมทั้งสิ้น</label>
                                            </div>
                                        </div>

                                        <!-- ข้อมูลวันที่บันทึก / แก้ไข / ผู้บันทึก -->
                                        <div class="mt-2 pt-2 border-top small text-muted d-flex justify-content-between flex-wrap">
                                            <span><i class="far fa-clock"></i> บันทึก: {{ $inv->created_at ? \Carbon\Carbon::parse($inv->created_at)->format('d/m/Y H:i') : '-' }}</span>
                                            <span><i class="fas fa-user-edit"></i> {{ $recorderName !== '' ? $recorderName : '-' }} ({{ $inv->recorder_id ?? '-' }})</span>
                                        </div>

                                        <div id="user_history_{{ $i }}" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            <?php $i++; ?>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
// 1. ฟังก์ชันคำนวณค่าน้ำ (สูตรเดียวกับหน้า zone_create)
function calculateRow(water_used, pricing_type, fixed_price, tiers_json, vat_rate, reserve_price, charge_min_only_zero = 0) {
    let safe_water = isNaN(parseFloat(water_used)) ? 0 : parseFloat(water_used);
    let water_cost = 0;
    if (safe_water < 0) {
        water_cost = 0;
    } else {
        if (tiers_json && Array.isArray(tiers_json) && tiers_json.length > 0 && pricing_type != 1) {
            let remaining_water = safe_water;
            for (let i = 0; i < tiers_json.length; i++) {
                if (remaining_water <= 0) break;
                let tier = tiers_json[i];
                let min = parseFloat(tier.min_units);
                let max = parseFloat(tier.max_units);
                let rate = parseFloat(tier.rate_per_unit);
                let range_size = (max > 0) ? (max - min + (min === 0 ? 0 : 1)) : Infinity;
                if (min === 0 && max === 10) range_size = 10;
                else if (max > 0) range_size = max - min + 1;
                let units_in_tier = Math.min(remaining_water, range_size);
                water_cost += units_in_tier * rate;
                remaining_water -= units_in_tier;
            }
        } else {
            water_cost = safe_water * parseFloat(fixed_price);
        }
    }

    // ตรวจสอบเงื่อนไข charge_min_only_zero_use:
    // หาก charge_min_only_zero == 1 เมื่อมีการใช้น้ำ (safe_water > 0) จะไม่คิดค่ารักษามิเตอร์ (reserve = 0)
    let effective_reserve = parseFloat(reserve_price) || 0;
    if (parseInt(charge_min_only_zero) === 1 && safe_water > 0) {
        effective_reserve = 0;
    }

    let vat_multiplier = (vat_rate > 1) ? (vat_rate / 100) : vat_rate;
    let taxable_amount = water_cost + effective_reserve;
    let vat_amount = taxable_amount * vat_multiplier;
    let total = water_cost + effective_reserve + vat_amount;

    return {
        paid: water_cost.toFixed(2),
        vat: vat_amount.toFixed(2),
        total: total.toFixed(2),
        reserve: effective_reserve.toFixed(2)
    };
}

function del(id) {
    if (confirm('ต้องการลบข้อมูลใบแจ้งหนี้นี้ใช่หรือไม่ !!!')) {
        window.location.href = '/invoice/delete/' + id + '/ลบ';
    }
}

function formatHistory(d) {
    let text = '<div class="table-responsive mt-2"><table class="table table-striped table-sm bg-white border"><thead><tr class="bg-light"><th style="font-size:11px;">รอบบิล</th><th style="font-size:11px;">ยอดเดิม</th><th style="font-size:11px;">ยอดใหม่</th><th style="font-size:11px;">หน่วย</th><th style="font-size:11px;">เงิน</th><th style="font-size:11px;">สถานะ</th></tr></thead><tbody>';
    if (d[0] && d[0].usermeterinfos && d[0].usermeterinfos.invoice) {
        d[0].usermeterinfos.invoice.forEach(element => {
            let _status = element.status === 'paid' ? '<span class="badge badge-success">จ่ายแล้ว</span>' : '<span class="badge badge-warning">ค้าง</span>';
            if (element.status !== 'init') {
                let invName = element.invoice_period ? (element.invoice_period.inv_p_name || '-') : '-';
                let waterUsed = (element.currentmeter || 0) - (element.lastmeter || 0);
                text += '<tr>' +
                    '<td style="font-size:11px;">' + invName + '</td>' +
                    '<td style="font-size:11px;">' + (element.lastmeter || 0) + '</td>' +
                    '<td style="font-size:11px;">' + (element.currentmeter || 0) + '</td>' +
                    '<td style="font-size:11px;">' + waterUsed + '</td>' +
                    '<td style="font-size:11px;">' + (element.totalpaid || 0) + '</td>' +
                    '<td style="font-size:11px;">' + _status + '</td>' +
                    '</tr>';
            }
        });
    } else {
        text += '<tr><td colspan="6" class="text-center text-muted small">ไม่พบประวัติย้อนหลัง</td></tr>';
    }
    text += '</tbody></table></div>';
    return text;
}

$(document).ready(function() {
    // 2. Filterable Card Search
    $('#cardFilterInput').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $('#cardContainer .filter-item').filter(function() {
            let searchData = $(this).data('search') || '';
            $(this).toggle(searchData.indexOf(value) > -1);
        });
    });

    // 3. Logic การคำนวณเมื่อแก้ไขเลขมิเตอร์
    $(document).on('keyup', '.currentmeter, .lastmeter', function() {
        try {
            let id = $(this).data('id');
            let currentInput = $('#currentmeter' + id);
            let lastInput = $('#lastmeter' + id);

            // ทำเครื่องหมายว่าการ์ดนี้ถูกแก้ไขแล้ว
            $('#changevalue' + id).val(1);
            currentInput.closest('.filter-item').attr('data-edited', '1');
            $('#card_box_' + id).removeClass('border-warning').addClass('card-edited');

            let currentmeter = parseFloat(currentInput.val()) || 0;
            let lastmeter = parseFloat(lastInput.val()) || 0;
            let water_used = currentmeter - lastmeter;

            let pricing_type = currentInput.data('pricing-type');
            let price_per_unit = parseFloat(currentInput.data('price')) || 0;
            let tiers_data = currentInput.data('tiers');
            let vat_rate = parseFloat(currentInput.data('vat')) || 0;
            let reserve_price = parseFloat(currentInput.data('reserve')) || 0;
            let charge_min_only_zero = parseInt(currentInput.data('charge-min-only-zero')) || 0;

            let waterUsedField = $('#water_used_net' + id);
            waterUsedField.val(water_used);

            if (water_used < 0) {
                waterUsedField.addClass('input-error');
            } else {
                waterUsedField.removeClass('input-error');
            }

            const result = calculateRow(water_used, pricing_type, price_per_unit, tiers_data, vat_rate, reserve_price, charge_min_only_zero);
            $('#paid' + id).val(result.paid);
            $('#vat' + id).val(result.vat);
            $('#total' + id).val(result.total);
            $('#meter_reserve_price' + id).val(result.reserve);
        } catch (error) {
            console.error("Calculation Error:", error);
        }
    });

    $(document).on('change', '.currentmeter, .lastmeter', function() {
        let id = $(this).data('id');
        let currentInput = $('#currentmeter' + id);
        let lastInput = $('#lastmeter' + id);
        let currentmeter = parseFloat(currentInput.val()) || 0;
        let lastmeter = parseFloat(lastInput.val()) || 0;
        let water_used = currentmeter - lastmeter;

        if (water_used < 0) {
            alert('❌ ผิดพลาด: จำนวนการใช้น้ำติดลบไม่ได้');
            setTimeout(function() {
                currentInput.focus();
            }, 100);
            return;
        }

        if (lastmeter > 0 && currentmeter > (lastmeter * 5)) {
            let isConfirmed = confirm('⚠️ แจ้งเตือน: มิเตอร์ปัจจุบัน (' + currentmeter + ') มากกว่าครั้งก่อน (' + lastmeter + ') ถึง 5 เท่า\nยืนยันข้อมูลนี้หรือไม่?');
            if (!isConfirmed) {
                setTimeout(function() {
                    currentInput.focus().select();
                }, 100);
                currentInput.addClass('input-warning');
            } else {
                currentInput.removeClass('input-warning');
            }
        }
    });

    // 4. History Toggle เมื่อคลิกชื่อผู้ใช้น้ำ
    $(document).on('click', '.username', function() {
        let user_id = $(this).data('user_id');
        let cardBox = $(this).closest('.card-body');
        let historyDiv = cardBox.find('[id^="user_history_"]');
        if (historyDiv.html().trim() !== '') {
            historyDiv.html('');
            return;
        }
        historyDiv.html('<div class="text-center p-2"><i class="fas fa-spinner fa-spin"></i> กำลังโหลดข้อมูล...</div>');
        $.get('/api/users/user/' + user_id)
            .done(function(data) {
                historyDiv.html(formatHistory(data));
            })
            .fail(function() {
                historyDiv.html('<div class="text-center text-danger p-2">โหลดข้อมูลไม่สำเร็จ</div>');
            });
    });

    // 5. ดักจับการ Submit ฟอร์ม (ส่งเฉพาะการ์ดที่มีการแก้ไขและเลขมิเตอร์ไม่ติดลบ)
    $('#invoiceEditForm').on('submit', function(e) {
        let hasChange = false;

        $('#cardContainer .filter-item').each(function() {
            let card = $(this);
            let currentInput = card.find('.currentmeter');
            let lastInput = card.find('.lastmeter');
            let changeInput = card.find('.changevalue-input');

            let originalCurrent = parseFloat(currentInput.attr('data-original')) || 0;
            let originalLast = parseFloat(lastInput.attr('data-original')) || 0;

            let currentVal = parseFloat(currentInput.val()) || 0;
            let lastVal = parseFloat(lastInput.val()) || 0;

            let isChanged = (currentVal !== originalCurrent) || (lastVal !== originalLast) || (card.attr('data-edited') === '1') || (changeInput.val() == '1');

            if (isChanged && currentVal >= lastVal) {
                changeInput.val(1);
                hasChange = true;
            } else {
                card.find('input, select, textarea').prop('disabled', true);
            }
        });

        if (!hasChange) {
            alert('⚠️ ไม่พบข้อมูลที่มีการแก้ไข กรุณาแก้ไขเลขมิเตอร์ก่อนกดบันทึก');
            $('#cardContainer .filter-item').find('input, select, textarea').prop('disabled', false);
            e.preventDefault();
        }
    });
});
</script>
@endsection
