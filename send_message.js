const puppeteer = require('puppeteer');

async function sendWhatsAppMessage(phoneNumber, message) {
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();
    
    try {
        await page.goto('https://web.whatsapp.com', { waitUntil: 'networkidle0' });

        console.log('Please scan the QR code within the browser.');

        // Wait for the QR code scan and user login
        await page.waitForSelector('canvas', { timeout: 60000 }); // QR code canvas

        console.log('Waiting for login to complete...');
        await page.waitForSelector('._3m_Xw', { timeout: 600000 }); // Main WhatsApp container

        // Navigate to the chat with the specified phone number
        const chatUrl = `https://web.whatsapp.com/send?phone=${phoneNumber}&text=${encodeURIComponent(message)}`;
        await page.goto(chatUrl, { waitUntil: 'networkidle0' });

        // Wait for the message input box to be loaded
        await page.waitForSelector('._2A8P4', { timeout: 10000 });

        // Focus on the message input box and send the message
        await page.click('._2A8P4');
        await page.keyboard.press('Enter');

        console.log('Message sent successfully.');

    } catch (error) {
        console.error('An error occurred:', error);
    } finally {
        // Optionally, close the browser after sending the message
        await browser.close();
    }
}

// Example usage
const phoneNumber = '919413029499'; // Replace with the recipient's phone number, including country code
const message = 'Hello, this is a test message!';
sendWhatsAppMessage(phoneNumber, message).catch(console.error);
