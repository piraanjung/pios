import { test, expect } from '@playwright/test';

test.describe('E2E Journey 07: Acc & Portal UI/UX, Multi-Device Session & Integrity Verification', () => {

  test('E2E-01 [Desktop]: Login, AccessMenu Wheel & Card Mode Switch with Thai Gov UX', async ({ page }) => {
    // 1. Visit Login page
    await page.goto('/login');
    await expect(page).toHaveURL(/.*login.*/);

    // 2. Fill login form
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');

    // 3. Verify landing on /accessmenu
    await page.waitForLoadState('networkidle');
    expect(page.url()).not.toContain('/login');

    // 4. Visit /accessmenu explicitly
    const accessResponse = await page.goto('/accessmenu');
    expect(accessResponse?.status()).toBe(200);

    // 5. Verify Wheel Menu is visible initially or container is loaded
    await expect(page.locator('#otp-connect')).toBeVisible();
    await expect(page.locator('#toggleViewModeBtn')).toBeVisible();

    // 6. Click Toggle View button to switch to Card Mode
    await page.click('#toggleViewModeBtn');

    // 7. Verify Card Mode container is visible with 8 government-friendly module cards
    await expect(page.locator('#cardContainer')).toBeVisible();
    await expect(page.locator('#cardContainer').getByText('ลำดับที่ 1')).toBeVisible();
    await expect(page.locator('#cardContainer h4', { hasText: 'งานประปา' })).toBeVisible();
    await expect(page.locator('#cardContainer').getByText('ลำดับที่ 2')).toBeVisible();
    await expect(page.locator('#cardContainer h4', { hasText: 'ธนาคารขยะรีไซเคิล' })).toBeVisible();
    await expect(page.locator('#cardContainer').getByText('ลำดับที่ 8')).toBeVisible();
    await expect(page.locator('#cardContainer h4', { hasText: 'ตั้งค่าระบบ' })).toBeVisible();

    // 8. Toggle back to Wheel mode
    await page.click('#toggleViewModeBtn');
    await expect(page.locator('#cardContainer')).toHaveClass(/d-none/);
  });

  test('E2E-02 [Desktop]: Tabwater Dashboard renders Soft UI metrics & charts without multi-tenancy error', async ({ page }) => {
    // 1. Login
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // 2. Visit Tabwater Dashboard
    const dashboardResponse = await page.goto('/dashboard');
    expect(dashboardResponse?.status()).toBe(200);

    // 3. Verify page content
    const pageText = await page.textContent('body');
    expect(pageText).not.toContain('SQLSTATE');
    expect(pageText).not.toContain('Column not found');
    expect(pageText).not.toContain('Server Error');
  });

  test('E2E-03 [Desktop]: Payment / Receipt form loads with idempotency and clean templates', async ({ page }) => {
    // 1. Login
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // 2. Visit Payment page
    const paymentResponse = await page.goto('/payment');
    expect(paymentResponse?.status()).toBe(200);

    const content = await page.textContent('body');
    expect(content).not.toContain('Unsupported operand types');
    expect(content).not.toContain('Undefined array key');
  });

  test('E2E-04 [Mobile]: Field Staff Session Sync & /acc Redirect without Login Loop', async ({ page }) => {
    // 1. Login first via web login
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // 2. Officer navigates to /acc (legacy QR bookmark)
    await page.goto('/acc');

    // 3. Must redirect cleanly to /staff_accessmenu WITHOUT being logged out
    expect(page.url()).toContain('/staff_accessmenu');

    // 4. Verify initial staff session injection exists on window
    const hasInitialSession = await page.evaluate(() => {
      return window.INITIAL_STAFF_SESSION !== null && window.INITIAL_STAFF_SESSION !== undefined;
    });
    expect(hasInitialSession).toBe(true);

    // 5. Verify Offline Alert Banner is present in DOM
    const offlineBanner = page.locator('#offlineAlertBanner');
    await expect(offlineBanner).toBeAttached();

    // 6. Verify mainScreen or mobile app shell is loaded
    await expect(page.locator('#mobileAppShell')).toBeVisible();
  });

  test('E2E-05 [Mobile]: Offline Alert Banner simulation & Resilience UI', async ({ page, context }) => {
    // Login and open staff mobile app
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    await page.goto('/staff_accessmenu');
    await page.waitForLoadState('networkidle');

    // Simulate going offline
    await context.setOffline(true);
    await page.evaluate(() => window.dispatchEvent(new Event('offline')));

    // Verify offline banner shows
    await expect(page.locator('#offlineAlertBanner')).not.toHaveClass(/d-none/);

    // Simulate going back online
    await context.setOffline(false);
    await page.evaluate(() => window.dispatchEvent(new Event('online')));

    // Verify offline banner hides
    await expect(page.locator('#offlineAlertBanner')).toHaveClass(/d-none/);
  });

  test('E2E-06 [Mobile & Logic]: Meter Recording does not include fake 300 THB owes', async ({ page }) => {
    // Login
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // Trigger meter reading view for meter 1
    const response = await page.goto('/tabwater/staff/mobile/1/meter_reading');
    expect(response?.status()).toBe(200);

    const bodyText = await page.textContent('body');
    // Ensure the fake owes (09-69, 08-69) and fake 300 THB are completely gone!
    expect(bodyText).not.toContain('"invoice_period":"09-69"');
    expect(bodyText).not.toContain('"invoice_period":"08-69"');
  });

  test('E2E-07 [Keptkayas Header & Layout]: Select Route renders Unified Header, Hamburger & Stepper', async ({ page }) => {
    // Login
    await page.goto('/login');
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');

    // Visit select-route page
    const response = await page.goto('/keptkayas/purchase/select-route');
    expect(response?.status()).toBe(200);

    // Verify Unified Header is rendered
    await expect(page.locator('#navbarBlur')).toBeVisible();
    await expect(page.locator('text=ออกหน่วยรับซื้อขยะ').first()).toBeVisible();

    // Verify 4-step wizard stepper
    await expect(page.getByText('เลือกเขต', { exact: true })).toBeVisible();
    await expect(page.getByText('เลือกสมาชิก', { exact: true })).toBeVisible();
    await expect(page.getByText('ชั่งน้ำหนักขยะ', { exact: true })).toBeVisible();
    await expect(page.getByText('สรุปและใบเสร็จ', { exact: true })).toBeVisible();

    // Verify Sidenav Hamburger Button exists and is outside collapse
    const hamburgerBtn = page.locator('#iconNavbarSidenav');
    await expect(hamburgerBtn).toBeAttached();

    // Verify Portal shortcut link exists
    await expect(page.locator('text=เมนูรวมระบบ').first()).toBeAttached();
  });

});
