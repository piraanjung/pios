# ADR 0008: การกำหนดมาตรฐานโครงสร้างการตอบกลับของ API (Standardized API Response Envelope Pattern)

## Status
Accepted (วันที่ 2026-11-05)

## Context
ในโค้ดเบสเดิมของโปรเจกต์ `qa_envsogo_pios` มีการส่งข้อมูลตอบกลับผ่าน API (`routes/api.php` และ Feature Endpoints) ในรูปแบบที่กระจัดกระจายและไม่เป็นระเบียบ เช่น:
1. บาง Endpoint ส่งคืน Array ดิบโดยตรง: `return response()->json($datas);`
2. บาง Endpoint ใช้คีย์เฉพาะกิจ: `return response()->json(['status' => 'not_found']);` หรือ `return response()->json(['user_info' => []], 404);`
3. บาง Endpoint ใช้ `success`: `return response()->json(['success' => false, 'message' => '...'], 422);`

ความไม่สม่ำเสมอ (Inconsistency) นี้ทำให้ผู้พัฒนาหน้าบ้าน (Frontend / Mobile App / LINE LIFF), อุปกรณ์ IoT (AIroBacT Kiosk, Smart Water Meter Nodes), และระบบภายนอก ต้องเขียนโค้ดดักจับและ Parse โครงสร้างข้อมูลที่แตกต่างกันในทุกๆ Endpoint ส่งผลให้เกิดบั๊กและเพิ่มภาระในการดูแลระบบ

## Decision
เราตัดสินใจกำหนดมาตรฐาน **Standardized API Response Envelope Pattern** สำหรับทุก API ในโปรเจกต์ โดย Response Body จะต้องประกอบด้วย 5 คีย์หลักเสมอ:

```json
{
  "success": true,
  "message": "ข้อความอธิบายผลลัพธ์ (ภาษาไทยหรืออังกฤษที่สื่อความหมาย)",
  "data": { ... },
  "errors": null,
  "meta": {
    "timestamp": "2026-11-05T13:49:11+07:00",
    "org_id": 1,
    "version": "v1"
  }
}
```

### โครงสร้างกรณีสำเร็จ (Success Envelope - 200, 201)
- `success`: `true` (boolean)
- `message`: ข้อความความสำเร็จ
- `data`: ข้อมูลที่ส่งคืน (Object, Array, หรือ `null`)
- `errors`: `null`
- `meta`: ข้อมูลประกอบบริบท (`timestamp`, `org_id`, `version`, และ `pagination` หากเป็นการแบ่งหน้า)

### โครงสร้างกรณีข้อผิดพลาด (Error Envelope - 400, 401, 403, 404, 422, 500)
- `success`: `false` (boolean)
- `message`: ข้อความอธิบายข้อผิดพลาดหลัก
- `data`: `null`
- `errors`: รายการข้อผิดพลาดเชิงลึก เช่น รายการ Validation Errors หรือ Error Codes (หรือ `null` หากไม่มี)
- `meta`: ข้อมูลประกอบบริบท (`timestamp`, `org_id`, `version`)

### เครื่องมือสนับสนุนใน Shared Kernel
1. คลาส `App\Shared\Http\ApiResponse`: ให้บริการ Static Methods มาตรฐาน
   - `ApiResponse::success($data, $message, $status, $meta)`
   - `ApiResponse::created($data, $message, $meta)`
   - `ApiResponse::error($message, $status, $errors, $meta)`
   - `ApiResponse::paginated($paginator, $message, $status)`
2. Trait `App\Shared\Http\Traits\HasApiResponse`: สำหรับนำไปใช้ใน API Controllers และ Vertical Slice Controllers
3. Response Macros: ลงทะเบียน `response()->apiSuccess()` และ `response()->apiError()`
4. Global Exception Handler / API Fallback: ดักจับ Validation Exception และ HTTP Exceptions เมื่อร้องขอผ่านช่องทาง API/JSON ให้จัดรูปแบบเป็น Envelope มาตรฐานนี้โดยอัตโนมัติ

## Consequences
- **Positive:**
  - ฝั่งหน้าบ้าน (Mobile App, LINE LIFF, Web, IoT Kiosk) เขียนโค้ด Interceptor / Response Handler รูปแบบเดียว ตรวจสอบ `if (res.data.success)` ได้อย่างมั่นใจ
  - มีบริบทองค์กร (`org_id`) และ `timestamp` ชัดเจนในทุก Response ช่วยให้ง่ายต่อการ Debug และตรวจสอบ Audit Log
  - เป็นไปตามมาตรฐานสถาปัตยกรรม RESTful API สากล
- **Negative:**
  - ต้องค่อยๆ ปรับ Legacy API Controllers เดิมให้หันมาใช้ Envelope มาตรฐานเมื่อมีการเข้าไปแก้ไข
- **Constraints (สำหรับ AI Agent):**
  - **ห้าม** ใช้ `return response()->json([...])` ที่มีโครงสร้างตามใจชอบใน API Controllers หรือ Feature Slices ใหม่อีกต่อไป
  - **บังคับ** ทุก API Endpoint ใหม่ใน `app/Features/` และ `app/Http/Controllers/Api/` จะต้องเรียกใช้ `ApiResponse::success()`, `ApiResponse::error()`, `ApiResponse::created()`, หรือ `ApiResponse::paginated()` เสมอ
  - **บังคับ** คีย์ทั้ง 5 (`success`, `message`, `data`, `errors`, `meta`) จะต้องปรากฏอยู่ใน Response Body เสมอ ห้ามละเว้น
