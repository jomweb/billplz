# Changelog for v4.x

This changelog references the relevant changes (bug and security fixes) done to `jomweb/billplz`.

## 4.0.0

Unreleased

### Added

* Added `Billplz\PaymentCompletion` implementation based on `Billplz\Contracts\PaymentCompletion` contract to handle registering redirect and callback URLs.

```php
new Billplz\PaymentCompletion(string $callbackUrl, ?string $redirectUrl = null);
```

### Changes

* Update minimum PHP version to 7.4+.

### Removed

* Remove deprecated `Billplz\Client::massPaymentCollection()` method and `Billplz\Four\Collection\MassPayment` implementation.
* Remove deprecated `Billplz\Client::massPayment()` method and `Billplz\Four\MassPayment` implementation.
* Remove deprecated `Billplz\Base\Bill\Transaction::show()` method, use `get()` to fetch bill transactions.
