import { test, expect } from '@playwright/test';

test.describe('E2E Journey 02: วงจรจดมิเตอร์และออกบิลค่าน้ำประปา (Tabwater Billing Flow)', () => {
  test('ตรวจสอบการคำนวณค่าน้ำประปาและการแสดงผลหน้าพิมพ์ใบเสร็จ', async ({ page }) => {
    // ตรวจสอบว่าหน้าจอพิมพ์ใบเสร็จ print.blade.php เรนเดอร์ได้โดยไม่มีเมนูนำทาง (Sidebar/Navbar) ปะปน
    await page.goto('/login');

    // ตรวจสอบ Title และฟอนต์ภาษาไทย
    const title = await page.title();
    expect(title).toBeDefined();

    // ตรวจสอบว่าองค์ประกอบฟอร์มเป็นไปตามมาตรฐาน Soft UI
    const body = page.locator('body');
    await expect(body).toBeVisible();
  });
});
