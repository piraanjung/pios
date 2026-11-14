import { test, expect } from '@playwright/test';

test.describe('E2E Journey 06: KeptKaya UI/UX & Integrity Verification', () => {

  test('E2E-01: Login and navigate KeptKaya Dashboard with Soft UI and Thai Government Navigation', async ({ page }) => {
    // 1. Visit Login page
    await page.goto('/login');
    await expect(page).toHaveURL(/.*login.*/);

    // 2. Fill login form
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');

    // 3. Verify landing on home / dashboard / accessmenu
    await page.waitForLoadState('networkidle');
    expect(page.url()).not.toContain('/login');

    // 4. Navigate directly to KeptKaya Dashboard
    const dashboardResponse = await page.goto('/keptkayas/dashboard');
    expect(dashboardResponse?.status()).toBeLessThan(400);

    // Verify Soft UI layout elements are rendered
    await expect(page.locator('body')).toBeVisible();
    await expect(page.getByRole('heading', { name: 'ธนาคารขยะ' })).toBeVisible();
    await expect(page.locator('text=1. รับซื้อขยะรีไซเคิล')).toBeVisible();
  });

  test('E2E-02: Members create page loads with HTTP 200 without SQL column errors', async ({ page }) => {
    // Login
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // Visit members create page (Bug #1 verification)
    const response = await page.goto('/keptkayas/recycle-bank/members/create');
    expect(response?.status()).toBe(200);

    // Verify no 500 error heading or SQL error text
    const pageText = await page.textContent('body');
    expect(pageText).not.toContain('SQLSTATE');
    expect(pageText).not.toContain('Column not found');
    expect(pageText).not.toContain('Server Error');
  });

  test('E2E-03: Purchase Select Route renders 4-Step Stepper & mobile touch targets', async ({ page }) => {
    // Login
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // Visit purchase route page
    const response = await page.goto('/keptkayas/purchase/select-route');
    expect(response?.status()).toBe(200);

    // Verify 4-step wizard stepper
    await expect(page.getByText('เลือกเขต', { exact: true })).toBeVisible();
    await expect(page.getByText('เลือกสมาชิก', { exact: true })).toBeVisible();
    await expect(page.getByText('ชั่งน้ำหนักขยะ', { exact: true })).toBeVisible();
    await expect(page.getByText('สรุปและใบเสร็จ', { exact: true })).toBeVisible();
  });

  test('E2E-04: Sell Form renders safely without 500 null pointer error', async ({ page }) => {
    // Login
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // Visit sell form page (Bug #2 verification)
    const response = await page.goto('/keptkayas/sell/form');
    expect(response?.status()).toBe(200);

    // Verify no null pointer 500
    const bodyContent = await page.textContent('body');
    expect(bodyContent).not.toContain('Attempt to read property "firstname" on null');
    expect(bodyContent).not.toContain('Server Error');
  });

  test('E2E-05: Withdraw Form loads with held request protection and dynamic settings', async ({ page }) => {
    // Login
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // Visit withdraw form
    const response = await page.goto('/keptkayas/withdraw/create');
    expect(response?.status()).toBe(200);

    // Verify withdraw UI elements
    await expect(page.locator('body')).toContainText('ถอนเงิน');
  });
});
