# Changelog for v3.x

This changelog references the relevant changes (bug and security fixes) done to `jomweb/billplz`.

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
