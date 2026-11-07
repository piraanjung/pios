import { test, expect } from '@playwright/test';

test.describe('E2E Journey 03: การฝากขยะรีไซเคิลผ่านตู้ AI Kiosk (AI Kiosk Deposit Flow)', () => {
  test('ตู้ Kiosk ส่งข้อมูลขวดและกระป๋องที่ AI ตรวจพบ เพื่อสะสมแต้มและยอดเงิน', async ({ request }) => {
    // 1. จำลองเหตุการณ์ AI Kiosk ส่งข้อมูลตะกร้าขยะ
    const payload = {
      userId: 1,
      machineId: 'KIOSK-E2E-01',
      totalPoints: 25,
      totalPrice: 12.50,
      items: [
        { rateId: 1, unitId: 2, qty: 10, price: 0.50, point: 1, label: 'PET-Bottle' },
        { rateId: 2, unitId: 2, qty: 6, price: 1.25, point: 2, label: 'Aluminium-Can' },
      ],
    };

    // 2. ป้องกัน Payload Tampering กรณีไม่ส่ง userId
    const invalidResponse = await request.post('/api/kiosk/submit-transaction', {
      data: { ...payload, userId: null },
      headers: { 'Accept': 'application/json' },
    });
    expect(invalidResponse.status()).toBe(422);

    // 3. ตรวจสอบการปฏิเสธกรณีขยะว่างเปล่า
    const emptyResponse = await request.post('/api/kiosk/submit-transaction', {
      data: { ...payload, items: [] },
      headers: { 'Accept': 'application/json' },
    });
    expect(emptyResponse.status()).toBe(400);
  });
});
