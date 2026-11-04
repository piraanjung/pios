{{-- Soft UI Dashboard Modal: เพิ่มรายชื่อประชาชนใหม่เข้าตารางหลัก (Master Users) --}}
<div class="modal fade" id="quickCreateMasterUserModal" tabindex="-1" role="dialog" aria-labelledby="quickCreateMasterUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary">
                <h6 class="modal-title text-white mb-0" id="quickCreateMasterUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>เพิ่มรายชื่อประชาชนใหม่เข้าฐานข้อมูลกลาง (Master Users)
                </h6>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.users.quick_store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info text-white text-xs border-0 mb-3 py-2">
                        <i class="fas fa-info-circle me-1"></i>
                        เมื่อบันทึกแล้ว รายชื่อจะถูกเพิ่มเข้าตาราง <strong>Master Users (`users`)</strong> ทันที และคุณสามารถติ๊กเลือกลงทะเบียนเข้าโมดูลนี้ได้ต่อทันที
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-xs font-weight-bold">คำนำหน้า</label>
                            <select name="prefix" class="form-select form-select-sm">
                                <option value="">-- เลือก --</option>
                                <option value="นาย">นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs font-weight-bold">ชื่อจริง <span class="text-danger">*</span></label>
                            <input type="text" name="firstname" class="form-control form-control-sm" placeholder="ระบุชื่อจริง" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label text-xs font-weight-bold">นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" name="lastname" class="form-control form-control-sm" placeholder="ระบุนามสกุล" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs font-weight-bold">เลขบัตรประชาชน (13 หลัก)</label>
                            <input type="text" name="id_card" class="form-control form-control-sm" maxlength="13" placeholder="เลขประจำตัวประชาชน 13 หลัก">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs font-weight-bold">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" class="form-control form-control-sm" maxlength="15" placeholder="08x-xxx-xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs font-weight-bold">บ้านเลขที่ / ที่อยู่</label>
                            <input type="text" name="address" class="form-control form-control-sm" placeholder="เช่น 123/4 หมู่ 1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs font-weight-bold">โซน / ชุมชน (Zone)</label>
                            <select name="zone_id" class="form-select form-select-sm">
                                <option value="">-- เลือกโซน/ชุมชน --</option>
                                @isset($zones)
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-gray-100 border-0">
                    <button type="button" class="btn bg-gradient-secondary btn-sm mb-0" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">
                        <i class="fas fa-save me-1"></i> บันทึกเข้าฐานข้อมูลกลาง (Master Users)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

