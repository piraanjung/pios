import { test, expect } from '@playwright/test';

test.describe('E2E Journey 04: พอร์ทัลสมาชิก LINE LIFF & Security IDOR Verification', () => {
  test('ป้องกันการเจาะระบบข้ามองค์กร (Cross-Tenant) และการสวมรอยบัญชีผู้ดูแล', async ({ request }) => {
    // 1. เรียกดู Preference ข้ามองค์กร (ต้องได้ 404 Not Found)
    const crossTenantProbe = await request.get('/line/dashboard/999999/999');
    expect(crossTenantProbe.status()).toBe(404);

    // 2. ป้องกันการเข้าถึงบัญชีผู้ดูแลผ่าน Public LIFF URL โดยตรง (ต้องได้ 403 หรือ 404)
    const adminProbe = await request.get('/line/dashboard/1/1', {
      headers: {
        'X-Line-User-Id': 'U_MALICIOUS_PROBE',
      },
    });
    expect([403, 404]).toContain(adminProbe.status());
  });
});
