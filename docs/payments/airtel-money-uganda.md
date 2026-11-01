# Airtel Money Uganda

This gateway uses Airtel Africa's Collection API to send a USSD payment prompt
and checks the transaction status with Airtel before marking an order as paid.
It accepts UGX payments only.

## Airtel setup

1. Create an application in the [Airtel Africa Developer Portal](https://developers.airtel.africa/developer).
2. Select Uganda and subscribe the application to Airtel Money Collection APIs.
3. Obtain the Client ID and Client Secret for the sandbox, then the live credentials after Airtel approves production access.

## App setup

1. Apply the database migration with `php artisan migrate`. Fresh installations also receive the inactive gateway from the installer seed.
2. In the admin panel, open **Third-party → Payment method**, configure **Airtel Money**, enter the credentials, and leave it in **Test** mode until sandbox payments succeed.
3. Enable UGX as an active store currency. Airtel Money is hidden from checkout if UGX is unavailable.
4. Switch the gateway to **Live** only after Airtel grants production access and you have completed an end-to-end test.

Credentials are entered in the app's existing payment-gateway settings, not committed to this repository. The gateway must be explicitly enabled in the admin panel.

## API behavior

- Sandbox base URL: `https://openapiuat.airtel.africa`
- Production base URL: `https://openapi.airtel.africa`
- Collection request: `POST /merchant/v1/payments/`
- Status verification: `GET /standard/v1/payments/{transactionId}`
- A customer must approve the USSD prompt on their Airtel Money phone.
- The browser polls the server for up to six minutes. A pending or ambiguous provider response is never treated as a successful payment.
- A payment is marked paid only after Airtel reports a successful status for the matching transaction reference.

The Airtel portal's country-specific API documentation and merchant approval are authoritative for the account's current access and limits.
