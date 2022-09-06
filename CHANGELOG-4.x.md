# Changelog for v4.x

This changelog references the relevant changes (bug and security fixes) done to `jomweb/billplz`.

## 4.4.0

Released: 2022-02-08

### Changes

* Improves generic type hint.

### Fixes

* Fixes `Laravie\Codex\Request::getApiEndpoint()` usage.

## 4.3.1

Released: 2020-12-28

### Changes

* Bump Codex dependencies.

## 4.3.0

Released: 2020-12-28

### Changes

* Update to support PHP 8.

## 4.2.0

Released: 2020-04-03

### Changes

* Merge `$optional['redirect_url']` when constructing `Billplz\PaymentCompletion` for `Bill` resource.
* Update sandbox API endpoint to `https://www.billplz-sandbox.com/api`.

## 4.1.0

Released: 2020-02-17

### Added

* Added `Billplz\Signature::create()` method.
* Add `WEBHOOK_PARAMETERS` and `REDIRECT_PARAMETERS` to `Billplz\Signature`.

## 4.0.0

Released: 2020-01-06

### Added

* Added `Billplz\PaymentCompletion` implementation based on `Billplz\Contracts\PaymentCompletion` contract to handle registering redirect and callback URLs.

```php
new Billplz\PaymentCompletion(string $callbackUrl, ?string $redirectUrl = null);
```

### Changes

* Update minimum PHP version to 7.2+.

### Removed

* Remove deprecated `Billplz\Client::massPaymentCollection()` method and `Billplz\Four\Collection\MassPayment` implementation.
* Remove deprecated `Billplz\Client::massPayment()` method and `Billplz\Four\MassPayment` implementation.
* Remove deprecated `Billplz\Base\Bill\Transaction::show()` method, use `get()` to fetch bill transactions.
