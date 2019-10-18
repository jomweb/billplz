# Changelog for v3.x

This changelog references the relevant changes (bug and security fixes) done to `jomweb/billplz`.

## 3.3.1

Released: 2019-10-18

### Fixes

* Fixes `Billplz\Base\PaymentCompletion::redirect()` when applicaiton is configured without X-Signature support.

## 3.3.0

Released: 2019-10-12

### Added

* Added `Billplz\Contracts\Card` contract and it's implementation based on [Card Tokenization API](https://www.billplz.com/api#card-tokenization).
* Added `Billplz\Exceptions\ExceedRequestLimits` exception class when response received `429` HTTP status code.
* Added `Billplz\Client::card()` helper method.
* Added `rateLimit()`, `remainingRateLimit()` and `rateLimitNextReset()` to `Billplz\Response` class.
* Added `Billplz\Four\Bill::charge()` to make payment via Card Tokenization based on [Payment with Token API](https://www.billplz.com/api#payment-with-token).

### Changes

* Improves POST with `Laravie\Codex\Concerns\Request\Multipart::stream()` usages.

## 3.2.1

Released: 2019-09-26

### Changes

* Only process `logo` options when creating Collection on `Billplz\Three\Collection` request.
* Refactor proxy requests on `Billplz\Four\Client`.

## 3.2.0

Released: 2019-08-28

### Added

* Added `Billplz\Contracts\Payout` and `Billplz\Contracts\Collection\Payout` contract and it's implementation.
* Added `Billplz\Client::payout()` and `Billplz\Client::payoutCollection()`.

### Deprecated

* Added `Billplz\Contracts\MassPayment` and `Billplz\Contracts\Collection\MassPayment` contract and it's implementation.
* Added `Billplz\Client::massPayment()` and `Billplz\Client::massPaymentCollection()`.

## 3.1.1

Released: 2019-05-09

### Added

* Added `Billplz\Four\PaymentGateway` based on [Get Payment Gateways API](https://www.billplz.com/api#get-payment-gateways).

## 3.1.0

Released: 2019-03-31

### Changes

* Update Laravie Codex to v5.0+.

## 3.0.3

Released: 2019-05-24

### Added

* Added `Billplz\Four\PaymentGateway` based on [Get Payment Gateways API](https://www.billplz.com/api#get-payment-gateways).

## 3.0.2

Released: 2019-03-31

### Changes

* Bump `jomweb/ringgit` dependencies to `v2.1.+`.

## 3.0.1

Released: 2019-03-02

### Changes

* Improve performance by prefixing all global functions calls with `\` to skip the look up and resolve process and go straight to the global function.

## 3.0.0

Released: 2018-12-13

### Added

* Add `Billplz\Contracts` interfaces for easier integration.

### Changes

* Update Laravie Codex to v4.0+.
* Change `Billplz\Base\Bank` to `Billplz\Base\BankAccount`.
* Rename `Billplz\Base\Bank::checkAccount()` to `Billplz\Base\BankAccount::check()`.

### Removed

* Remove `Billplz\Client::check()`, use `Billplz\Client::bank()->check()` instead.
* Remove `Billplz\Base\Check`, use `Billplz\Base\BankAccount`.
