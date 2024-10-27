=== AI Chatbot, Live Chat, & Lead Generation for WordPress using ChatGPT ===
Contributors: robofyaichatbot
Tags: AI,chatbot,ChatGPT,live chat,open ai
Requires at least: 5.6.0
Requires PHP: 7.0
Tested up to: 6.5.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create a personalized AI chatbot powered by GPT with your website content. Instantly answer visitors' questions to grow business.

# External Service Usage

This plugin uses a 3rd party service, [robofy.ai](https://robofy.ai), to check status of user's account. When user enter email id, the plugin sends data to the following endpoint:

- **Service URL:** `http://robofy.ai/svc.asmx/getotpdetails`
- **Data Sent:** The user's email address is transmitted to the service to check if user account detail is valid or not.

- **Service URL:** `http://robofy.ai/svc.asmx/ValidOtpDetails`
- **Data Sent:** Use enter otp and transmitted to service to check if user has valid OTP or not.

- **Service URL:** `https://api.robofy.ai/v1/add-website-v2`
- **Data Sent:** User add website to create chatbot so this service take user's website to create chatbot.

- **Service URL:** `https://webapi.robofy.ai/api/UnAuthorized/get-plan`
- **Data Sent:** to check user's plan.

- **Service URL:** `https://api.robofy.ai/v1/bot-ready-status-cartbox`
- **Data Sent:** User add website to create chatbot so this service take user's website to create chatbot and than return script to display chatbot.

## Legal Information

It's important to be aware of the terms of use and privacy policy of [Whatso.net]:

- **[robofy.ai Terms of Use](https://robofy.ai/terms)**
- **[robofy.ai  Privacy Policy](https://robofy.ai/privacy)**


== Description ==

AI Chatbot by Robofy is a powerful plugin that enables WordPress & WooCommerce website owners to automate conversations with their customers using conversational AI technology.

**✨ What is AI chatbot? ✨**

**AI chatbot is an advanced chatbot solution specifically designed for websites. It uses AI technology to provide instant and accurate responses to all visitor queries.**

**Whether your visitors are looking for product information, need support, or have general inquiries, the AI chatbot plugin for the website, known as Robofy, has got it covered.**

**✨ What are the additional settings in this WordPress ChatBot Plugin? ✨**

 * Setting to toggle User's IP Address to comply with GDPR
 * Choose Website Language to easily crawl data
 * Enter website details to answer smartly when the question asked by user is irrelevant
 * Choose any model between GPT 3.5 and GPT 4.0
 * Enter your own API key to use GPT 4
 * Play sound when an incoming message is received
 * Automatically open or close the chatbot widget on page load
 * Show or hide Chatbot on mobile
 * Automatically recrawl the website after certain number of days
 * Set custom messages when no relevant answer is found
 * Give additional instructions to personalize the answer
 * Use custom prompt to completely personalize the bot
 * Set Chatbot tone as per your own business
 * Set floating Chatbot position on right or left side
 * Set Chatbot color theme
 * Modify CSS for advanced customization
 * Upload Stand-Alone Chatbot on your website
 * Enable WhatsApp, Facebook Messenger, Email and Call Back Widgets
 * Enable Live Chat like Intercom.com, Tawk.to, Crisp.chat etc
 * Enable Calendly demo booking widget
 * Embed JavaScript Code
 * Enable Quick Questions to assist users
 * Add PDF, Microsoft Docs, WebPages
 * Supports any language including Right to left languages (RTL)
 * Upload your own custom icon for Chatbot
 * Easily set a welcome greeting message


**✨ AI CHATBOT FEATURE ✨**

**Conversational AI: AI Chatbot uses natural language processing and machine learning algorithms to understand and respond to customer queries and requests.**

**Automated conversations: The chatbot engages customers in automated conversations, guiding them through the sales process, answering frequently asked questions.**

**24/7 availability: AI Chatbot is available to customers 24/7, ensuring that businesses never miss an opportunity to engage with their audience.**

**Customizable Chatbot: Businesses can customize the chatbot to align with their brand identity and messaging.**

**Analytics: AI Chatbot provides businesses with detailed analytics on customer conversations, allowing them to optimize the chatbot's performance and improve the customer experience.**

**Multilingual Support: Our chatbot supports any language supported by the AI. The chat response language can also be set by the user.**

== Frequently Asked Questions ==

= How does the WordPress AI Chatbot work? =
The WordPress AI Chatbot is an advanced chatbot solution that uses artificial intelligence to communicate with website visitors and provide them with instant and accurate responses. It learns from your webpage content and interactions. Then it improves over time, becoming even more efficient at assisting users.

= What types of inquiries can the WordPress AI Chatbot handle? =
The WordPress AI Chatbot can handle a wide range of inquiries, including product information, support assistance, general inquiries, and more.

== Installation ==

= From Dashboard ( WordPress admin ) =
* plugins -> Add New
* search for 'Robofy AI Chatbot'
* click on Install Now and then Active.

== ❤️ Support / Contact =

 For any queries, please contact us at hi@robofy.ai or create a new topic on the WordPress plugin page.
 == Changelog ==
 = 2.0.0 =
 *Now user can crawl post,pages and product
 = 1.0.0 =
 *AI chatbot Plugin.