import { test, expect } from '@playwright/test';

test.describe('E2E Journey 01: การลงทะเบียนประชาชนแบบรวมศูนย์ (Unified Onboarding)', () => {
  test('สร้างบัญชีประชาชนพร้อมเปิดใช้งานธนาคารขยะและกองทุนสวัสดิการชุมชน', async ({ page, request }) => {
    // 1. ตรวจสอบ API Backend ให้บริการได้
    const health = await request.get('/api/health-check');
    expect(health.status()).toBe(200);

    // 2. ส่งคำขอบันทึกข้อมูลประชาชนผ่าน Vertical Slice API
    const uniqueSuffix = Date.now().toString().slice(-6) + Math.floor(Math.random() * 1000);
    const citizenPayload = {
      org_id_fk: 1,
      firstname: 'ธนากร',
      lastname: 'พัฒนาเมือง',
      prefix: 'นาย',
      id_card: `11005${uniqueSuffix}`.slice(0, 13),
      phone: `081${uniqueSuffix}`.slice(0, 10),
      email: `thanakorn_${uniqueSuffix}@envsogo.test`,
      address: '99/1 หมู่บ้านสิ่งแวดล้อมดี',
      enable_recycle_bank: true,
      enable_welfare: true,
      enable_food_waste: true,
    };

    const response = await request.post('/features/onboard-citizen', {
      data: citizenPayload,
      headers: {
        'Accept': 'application/json',
      },
    });

    expect(response.status()).toBe(201);
    const body = await response.json();

    expect(body.success).toBe(true);
    expect(body.data.account_no).toContain('ACC-0001-');
    expect(body.data.member_number).toContain('MBR-0001-');
  });
});
