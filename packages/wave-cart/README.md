# WaveCart

WaveCart is a lean and user-friendly store extension for TYPO3 that has been specially developed for websites with just a few items. It offers all the basic e-commerce functions without the
functionality and complexity of larger store systems. Ideal for individual products, small collections or digital goods, WaveCart can be easily integrated into existing TYPO3 pages and flexibly
adapted.

The project is based on TYPO3 v13, using Composer.

## Installation

```sh
composer require typo3-incubator/wave-cart
```
Add the wave-cart siteset to the site configuration.

---

## Configuration

WaveCart introduces a set of configurable options that allow you to tailor the extension to your needs. These settings are divided into meaningful categories and defined in TYPO3's site configuration.
Below is an overview of the available configurations and their purposes. <br>

You can configure them in the backend under Site Management → Settings.

### General Settings for Cart and Order

These settings define where cart and order data should be stored, as well as the page IDs used by the shopping cart. <br>

- **Cart Page ID:** <br>
  Specifies the page ID where the shopping cart plugin is located. <br>
  **Setting:** `waveCart.cartPageId` <br>

- **Cart PID:** <br>
  Defines the PID (Page ID) where cart objects should be stored. <br>
  **Setting:** `waveCart.cartPid` <br>

- **Cart Items PID:** <br>
  Defines the PID (Page ID) where cart items should be stored. <br>
  **Setting:** `waveCart.cartItemPid` <br>

- **Order PID:** <br>
  Defines the PID where order data should be stored. <br>
  **Setting:** `waveCart.orderPid` <br>

- **Order Items PID:** <br>
  Specifies the PID for storing individual order item data. <br>
  **Setting:** `waveCart.orderItemPid` <br>

---

### Email Configuration

These settings control the email notifications sent to customers and administrators. They allow customizing sender and receiver details for all communications.

- **From Email Address:** <br>
  Email address used for outgoing emails (e.g., noreply). <br>
  **Setting:** `waveCart.mailFromAddress` <br>

- **Sender Email Subject:** <br>
  Subject line for emails sent by the sender. <br>
  **Setting:** `waveCart.mailFromSubject` <br>

- **Receiver Email Address:** <br>
  Email address for receiving order notifications. <br>
  **Setting:** `waveCart.mailReceiverAddress` <br>

- **Receiver Email Subject:** <br>
  Subject line for emails sent to the admin. <br>
  **Setting:** `waveCart.mailReceiverSubject` <br>

---

### Invoice Configuration

Control how invoices are generated for orders. These settings allow you to define company and banking details for proper invoice formatting.

- **Company Name:** <br>
  Name of the company generating the invoice. <br>
  **Setting:** `waveCart.invoiceCompanyName` <br>

- **Company Street:** <br>
  Address of the company generating the invoice. <br>
  **Setting:** `waveCart.invoiceCompanyStreet` <br>

- **Company City:** <br>
  City where the company is located. <br>
  **Setting:** `waveCart.invoiceCompanyCity` <br>

- **Company Phone:** <br>
  Phone number of the company for contact purposes. <br>
  **Setting:** `waveCart.invoiceCompanyPhone` <br>

- **IBAN:** <br>
  International Bank Account Number for payments. <br>
  **Setting:** `waveCart.invoiceIban` <br>

- **BIC:** <br>
  Bank Identifier Code for the company's bank. <br>
  **Setting:** `waveCart.invoiceBic` <br>

- **Bank Name:** <br>
  Name of the bank managing the company's account. <br>
  **Setting:** `waveCart.invoiceBankName` <br>

- **Account Holder:** <br>
  Name of the individual or entity holding the account. <br>
  **Setting:** `waveCart.invoiceAccountHolder` <br>

---

### License

WaveCart is distributed under the MIT license. For more details, please refer to the `LICENSE` file distributed with the project.
