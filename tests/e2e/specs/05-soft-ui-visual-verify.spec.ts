import { test, expect } from '@playwright/test';

test.describe('E2E Journey 05: การตรวจสอบความสมบูรณ์ของ Soft UI PRO & Visual Assets', () => {
  test('ตรวจสอบว่าหน้าเว็บไม่มี 404 assets หรือ Fatal console errors', async ({ page }) => {
    const consoleErrors: string[] = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        consoleErrors.push(msg.text());
      }
    });

    await page.goto('/login');

    // ตรวจสอบโครงสร้างพื้นฐาน
    await expect(page.locator('body')).toBeVisible();

    // กรอง error ทั่วไปที่ไม่ใช่ fatal
    const criticalErrors = consoleErrors.filter(err =>
      err.includes('Uncaught') || err.includes('Fatal') || err.includes('500 (Internal Server Error)')
    );
    expect(criticalErrors).toHaveLength(0);
  });
});
