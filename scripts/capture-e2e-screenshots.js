import { chromium } from '@playwright/test';
import fs from 'fs';
import path from 'path';

async function captureScreenshots() {
  const baseUrl = process.env.APP_URL || 'http://localhost:8080';
  const outputDir = path.resolve('docs/screenshots');
  const artifactDir = '/Users/poppy.geidtiphong/.gemini/antigravity/brain/6f957369-490b-4756-8ca7-b54d15f397a8/screenshots';

  for (const dir of [outputDir, artifactDir]) {
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }
  }

  const saveBoth = (sourcePath, filename) => {
    fs.copyFileSync(sourcePath, path.join(artifactDir, filename));
  };

  const browser = await chromium.launch();

  // 1. Desktop Soft UI PRO Dashboard (1440x900)
  console.log(`Capturing Desktop Soft UI PRO Dashboard from ${baseUrl}...`);
  const desktopContext = await browser.newContext({
    viewport: { width: 1440, height: 900 },
    deviceScaleFactor: 2,
  });
  const desktopPage = await desktopContext.newPage();
  await desktopPage.goto(`${baseUrl}/preview/soft-ui-pro`, { waitUntil: 'networkidle' });
  const desktopFile = path.join(outputDir, '01-soft-ui-pro-desktop-dashboard.png');
  await desktopPage.screenshot({
    path: desktopFile,
    fullPage: true,
  });
  saveBoth(desktopFile, '01-soft-ui-pro-desktop-dashboard.png');
  await desktopContext.close();

  // 2. Mobile Responsive View (390x844 - iPhone 14)
  console.log(`Capturing Mobile Responsive View from ${baseUrl}...`);
  const mobileContext = await browser.newContext({
    viewport: { width: 390, height: 844 },
    isMobile: true,
    hasTouch: true,
    deviceScaleFactor: 2,
  });
  const mobilePage = await mobileContext.newPage();
  await mobilePage.goto(`${baseUrl}/preview/soft-ui-pro`, { waitUntil: 'networkidle' });
  const mobileFile = path.join(outputDir, '02-soft-ui-pro-mobile-view.png');
  await mobilePage.screenshot({
    path: mobileFile,
    fullPage: true,
  });
  saveBoth(mobileFile, '02-soft-ui-pro-mobile-view.png');
  await mobileContext.close();

  // 3. Login Screen (1440x900)
  console.log(`Capturing Login Screen from ${baseUrl}...`);
  const loginContext = await browser.newContext({
    viewport: { width: 1440, height: 900 },
    deviceScaleFactor: 2,
  });
  const loginPage = await loginContext.newPage();
  await loginPage.goto(`${baseUrl}/login`, { waitUntil: 'networkidle' });
  const loginFile = path.join(outputDir, '03-login-screen.png');
  await loginPage.screenshot({
    path: loginFile,
    fullPage: false,
  });
  saveBoth(loginFile, '03-login-screen.png');
  await loginContext.close();

  // 4. Kiosk Touch Screen (1080x1920)
  console.log(`Capturing Kiosk Screen from ${baseUrl}...`);
  const kioskContext = await browser.newContext({
    viewport: { width: 1080, height: 1920 },
    hasTouch: true,
    deviceScaleFactor: 1.5,
  });
  const kioskPage = await kioskContext.newPage();
  await kioskPage.goto(`${baseUrl}/kiosk_login`, { waitUntil: 'networkidle' });
  const kioskFile = path.join(outputDir, '04-kiosk-login-screen.png');
  await kioskPage.screenshot({
    path: kioskFile,
    fullPage: false,
  });
  saveBoth(kioskFile, '04-kiosk-login-screen.png');
  await kioskContext.close();

  await browser.close();
  console.log('All screenshots captured and copied to both docs/screenshots/ and artifacts directory successfully.');
}

captureScreenshots().catch(err => {
  console.error('Error capturing screenshots:', err);
  process.exit(1);
});
