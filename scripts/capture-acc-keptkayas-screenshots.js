import { chromium, devices } from '@playwright/test';
import fs from 'fs';
import path from 'path';

async function capture() {
  const baseUrl = process.env.APP_URL || 'http://127.0.0.1:8000';
  const outputDir = path.resolve('docs/screenshots');
  const artifactDir = '/Users/poppy.geidtiphong/.gemini/antigravity/brain/6f957369-490b-4756-8ca7-b54d15f397a8/screenshots';

  for (const dir of [outputDir, artifactDir]) {
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }
  }

  const saveBoth = (sourcePath, filename) => {
    fs.copyFileSync(sourcePath, path.join(artifactDir, filename));
    console.log(`Saved: ${filename}`);
  };

  const browser = await chromium.launch();

  // Helper login function
  async function login(page) {
    await page.goto(`${baseUrl}/login`);
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
  }

  // --- 1. Desktop: Keptkayas Select Route ---
  console.log('Capturing: Keptkayas Select Route (Desktop)...');
  const desktopCtx = await browser.newContext({
    viewport: { width: 1440, height: 900 },
    deviceScaleFactor: 2,
  });
  const desktopPage = await desktopCtx.newPage();
  await login(desktopPage);
  await desktopPage.goto(`${baseUrl}/keptkayas/purchase/select-route`, { waitUntil: 'networkidle' });
  const file05 = path.join(outputDir, '05-keptkayas-select-route-desktop.png');
  await desktopPage.screenshot({ path: file05, fullPage: true });
  saveBoth(file05, '05-keptkayas-select-route-desktop.png');

  // --- 2. Desktop: AccessMenu Card Mode & Wheel Mode ---
  console.log('Capturing: AccessMenu Card Mode (Desktop)...');
  await desktopPage.goto(`${baseUrl}/accessmenu`, { waitUntil: 'networkidle' });
  // Ensure Card Mode is toggled
  const cardContainer = desktopPage.locator('#cardContainer');
  const isCardVisible = await cardContainer.isVisible();
  if (!isCardVisible) {
    await desktopPage.click('#toggleViewModeBtn');
    await desktopPage.waitForTimeout(500);
  }
  const file07 = path.join(outputDir, '07-accessmenu-card-mode-desktop.png');
  await desktopPage.screenshot({ path: file07, fullPage: false });
  saveBoth(file07, '07-accessmenu-card-mode-desktop.png');

  console.log('Capturing: AccessMenu Wheel Mode (Desktop)...');
  await desktopPage.click('#toggleViewModeBtn');
  await desktopPage.waitForTimeout(500);
  const file08 = path.join(outputDir, '08-accessmenu-wheel-mode-desktop.png');
  await desktopPage.screenshot({ path: file08, fullPage: false });
  saveBoth(file08, '08-accessmenu-wheel-mode-desktop.png');

  // --- 3. Desktop: Tabwater Payment ---
  console.log('Capturing: Acc Payment Screen (Desktop)...');
  await desktopPage.goto(`${baseUrl}/payment`, { waitUntil: 'networkidle' });
  const file09 = path.join(outputDir, '09-acc-payment-desktop.png');
  await desktopPage.screenshot({ path: file09, fullPage: true });
  saveBoth(file09, '09-acc-payment-desktop.png');

  await desktopCtx.close();

  // --- 4. Mobile: Keptkayas Select Route (Pixel 7) ---
  console.log('Capturing: Keptkayas Select Route (Mobile)...');
  const mobileCtx = await browser.newContext({
    ...devices['Pixel 7'],
    viewport: { width: 390, height: 844 },
    deviceScaleFactor: 2,
  });
  const mobilePage = await mobileCtx.newPage();
  await login(mobilePage);
  await mobilePage.goto(`${baseUrl}/keptkayas/purchase/select-route`, { waitUntil: 'networkidle' });
  const file06 = path.join(outputDir, '06-keptkayas-select-route-mobile.png');
  await mobilePage.screenshot({ path: file06, fullPage: false });
  saveBoth(file06, '06-keptkayas-select-route-mobile.png');

  // --- 5. Mobile: Sidebar opened via Hamburger Menu ---
  console.log('Capturing: Keptkayas Mobile Sidebar Open...');
  const hamburger = mobilePage.locator('#iconNavbarSidenav');
  if (await hamburger.isVisible()) {
    await hamburger.click();
    await mobilePage.waitForTimeout(600);
  }
  const file06b = path.join(outputDir, '06b-keptkayas-mobile-sidebar-open.png');
  await mobilePage.screenshot({ path: file06b, fullPage: false });
  saveBoth(file06b, '06b-keptkayas-mobile-sidebar-open.png');

  // --- 6. Mobile: Meter Reading Screen ---
  console.log('Capturing: Acc Mobile Meter Reading...');
  await mobilePage.goto(`${baseUrl}/tabwater/staff/mobile/1/meter_reading`, { waitUntil: 'networkidle' });
  const file10 = path.join(outputDir, '10-acc-meter-reading-mobile.png');
  await mobilePage.screenshot({ path: file10, fullPage: true });
  saveBoth(file10, '10-acc-meter-reading-mobile.png');

  await mobileCtx.close();
  await browser.close();
  console.log('Successfully captured all E2E screenshots!');
}

capture().catch(err => {
  console.error('Error capturing screenshots:', err);
  process.exit(1);
});
