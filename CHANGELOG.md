# Changelog

This changelog references the relevant changes (bug and security fixes) done to `jomweb/billplz`.

## 1.0.5

Released: 2017-11-03

### Added

* Add `v4` API support.
* Add Mass Payment instruction support. ([@h2akim](https://github.com/h2akim))
* Add `Billplz\Signature` to allow reusing x-signature verification even without using the whole library.

### Changes

* Abstract common API request to `Billplz\Base` namespace to support multiple version API requests.

## 1.0.4

Released: 2017-10-30

### Added

* Add `Billplz\Three\Bank::checkAccount()` to handle checking account status as prefered alternative instead of `Billplz\Three\Check::bankAccount()`.
* Add `Billplz\Three\Bank::supportedForFpx()` to check list of banks support with FPX. ([@mrkaymy](https://github.com/mrkaymy))
* Add X-Signature verification for `Billplz\Three\Bill::webhook()` which will throws `Billplz\Exceptions\FailedSignatureVerification` exception if the hash is not equals.

## 1.0.3

Released: 2017-10-12

### Changes

* Allows to cast to `Duit\MYR`.

## 1.0.2

Released: 2017-10-08

### Added

* Add `Billplz\Client::check()` resource helper to make it easy to verify bank account via `$billplz->check()->bankAccount()`.

### Changes

* Improves readme/documentation.

## 1.0.1

Released: 2017-08-31

### Fixes

* `Billplz\Request::getApiEndpoint()` should properly handle `$path` when given an array.

## 1.0.0

Released: 2017-08-31

### New

* Initial stable release.

### Changes

* `Billplz\Three\Bill::create()` now support array for `$callbackUrl` parameter, when you can provide an associated array containing `callback_url` and `redirect_url`.
* Make `nesbot/carbon` dependency optional, simply use `new \DateTime()` if it is not presented.
