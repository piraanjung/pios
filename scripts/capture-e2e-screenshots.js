import { chromium } from '@playwright/test';
import fs from 'fs';
import path from 'path';

async function captureScreenshots() {
  const outputDir = path.resolve('docs/screenshots');
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  const browser = await chromium.launch();

  // 1. Desktop Soft UI PRO Dashboard (1440x900)
  console.log('Capturing Desktop Soft UI PRO Dashboard...');
  const desktopContext = await browser.newContext({
    viewport: { width: 1440, height: 900 },
    deviceScaleFactor: 2,
  });
  const desktopPage = await desktopContext.newPage();
  await desktopPage.goto('http://127.0.0.1:8088/preview/soft-ui-pro', { waitUntil: 'networkidle' });
  await desktopPage.screenshot({
    path: path.join(outputDir, '01-soft-ui-pro-desktop-dashboard.png'),
    fullPage: true,
  });
  await desktopContext.close();

  // 2. Mobile Responsive View (390x844 - iPhone 14)
  console.log('Capturing Mobile Responsive View...');
  const mobileContext = await browser.newContext({
    viewport: { width: 390, height: 844 },
    isMobile: true,
    hasTouch: true,
    deviceScaleFactor: 2,
  });
  const mobilePage = await mobileContext.newPage();
  await mobilePage.goto('http://127.0.0.1:8088/preview/soft-ui-pro', { waitUntil: 'networkidle' });
  await mobilePage.screenshot({
    path: path.join(outputDir, '02-soft-ui-pro-mobile-view.png'),
    fullPage: true,
  });
  await mobileContext.close();

  // 3. Login Screen (1440x900)
  console.log('Capturing Login Screen...');
  const loginContext = await browser.newContext({
    viewport: { width: 1440, height: 900 },
    deviceScaleFactor: 2,
  });
  const loginPage = await loginContext.newPage();
  await loginPage.goto('http://127.0.0.1:8088/login', { waitUntil: 'networkidle' });
  await loginPage.screenshot({
    path: path.join(outputDir, '03-login-screen.png'),
    fullPage: false,
  });
  await loginContext.close();

  // 4. Kiosk Touch Screen (1080x1920)
  console.log('Capturing Kiosk Screen...');
  const kioskContext = await browser.newContext({
    viewport: { width: 1080, height: 1920 },
    hasTouch: true,
    deviceScaleFactor: 1.5,
  });
  const kioskPage = await kioskContext.newPage();
  await kioskPage.goto('http://127.0.0.1:8088/kiosk_login', { waitUntil: 'networkidle' });
  await kioskPage.screenshot({
    path: path.join(outputDir, '04-kiosk-login-screen.png'),
    fullPage: false,
  });
  await kioskContext.close();

  await browser.close();
  console.log('All screenshots captured successfully in docs/screenshots/');
}

captureScreenshots().catch(err => {
  console.error('Error capturing screenshots:', err);
  process.exit(1);
});
