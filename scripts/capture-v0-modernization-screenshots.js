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

  // --- 1. Desktop: Keptkayas Dashboard ---
  console.log('Capturing: Keptkayas Modern Dashboard (Desktop)...');
  const desktopCtx = await browser.newContext({
    viewport: { width: 1440, height: 1080 },
    deviceScaleFactor: 2,
  });
  const desktopPage = await desktopCtx.newPage();
  await login(desktopPage);

  await desktopPage.goto(`${baseUrl}/keptkayas/dashboard`, { waitUntil: 'networkidle' });
  await desktopPage.waitForTimeout(1000); // Allow Chart.js animations to complete
  const file11 = path.join(outputDir, '11-keptkayas-dashboard-desktop.png');
  await desktopPage.screenshot({ path: file11, fullPage: true });
  saveBoth(file11, '11-keptkayas-dashboard-desktop.png');

  // --- 2. Desktop: Select Organization with Faceted Toolbar ---
  console.log('Capturing: Select Org Default (Desktop)...');
  await desktopPage.goto(`${baseUrl}/admin/select_org`, { waitUntil: 'networkidle' });
  await desktopPage.waitForTimeout(500);
  const file13 = path.join(outputDir, '13-select-org-desktop.png');
  await desktopPage.screenshot({ path: file13, fullPage: true });
  saveBoth(file13, '13-select-org-desktop.png');

  // --- 3. Desktop: Select Organization with Real-time Filter Interaction ---
  console.log('Capturing: Select Org Filtered (Desktop)...');
  const searchInput = desktopPage.locator('#orgSearchInput');
  if (await searchInput.isVisible()) {
    await searchInput.fill('เทศบาล');
    await desktopPage.waitForTimeout(400);
  }
  const file14 = path.join(outputDir, '14-select-org-filtered-desktop.png');
  await desktopPage.screenshot({ path: file14, fullPage: true });
  saveBoth(file14, '14-select-org-filtered-desktop.png');

  await desktopCtx.close();

  // --- 4. Mobile: Keptkayas Dashboard (Pixel 7) ---
  console.log('Capturing: Keptkayas Modern Dashboard (Mobile)...');
  const mobileCtx = await browser.newContext({
    ...devices['Pixel 7'],
    viewport: { width: 390, height: 844 },
    deviceScaleFactor: 2,
  });
  const mobilePage = await mobileCtx.newPage();
  await login(mobilePage);

  await mobilePage.goto(`${baseUrl}/keptkayas/dashboard`, { waitUntil: 'networkidle' });
  await mobilePage.waitForTimeout(1000); // Allow Chart.js animations
  const file12 = path.join(outputDir, '12-keptkayas-dashboard-mobile.png');
  await mobilePage.screenshot({ path: file12, fullPage: true });
  saveBoth(file12, '12-keptkayas-dashboard-mobile.png');

  await mobileCtx.close();
  await browser.close();
  console.log('All screenshots captured successfully!');
}

capture().catch((err) => {
  console.error('Capture error:', err);
  process.exit(1);
});
