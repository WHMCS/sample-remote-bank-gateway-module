# WHMCS Sample Remote Input Bank Gateway Module #

## Summary ##

Payment Gateway modules allow you to integrate payment solutions with the WHMCS
platform.

The sample files here demonstrate how we suggest a Bank Gateway that uses
a remotely hosted payment page should be created for WHMCS.

For more information, please refer to the documentation at:
https://developers.whmcs.com/payment-gateways/remote-input-gateway/

## Remote Input Bank Module ##

A remote input bank module is a type of gateway that accepts input of pay
method data remotely within an iFrame so that it appears transparent to the end
user, and then exchanges it for a token that is returned back to WHMCS to be
stored for future billing attempts.

Within WHMCS, sensitive payment data such as a bank account number is not stored
locally when a remote input module is used.

For the purposes of this sample, a demo of a remotely hosted payment page is
provided within the `demo` directory.

In a real world scenario, this file/page would be hosted by the payment gateway
being implemented. On submission they would validate the input and return the
user to the callback file with a success confirmation.

## Recommended Module Content ##

The recommended structure of a remote input bank gateway module is as follows.

```
 modules/gateways/
  |- callback/remotebank.php
  |  remotebank.php
```

## Minimum Requirements ##

For the latest WHMCS minimum system requirements, please refer to
https://docs.whmcs.com/System_Requirements

We recommend your module follows the same minimum requirements wherever
possible.

## Useful Resources
* [Developer Resources](https://developers.whmcs.com/)
* [Hook Documentation](https://developers.whmcs.com/hooks/)
* [API Documentation](https://developers.whmcs.com/api/)

[WHMCS Limited](https://www.whmcs.com)