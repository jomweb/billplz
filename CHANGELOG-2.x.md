# Changelog for v2.x

This changelog references the relevant changes (bug and security fixes) done to `jomweb/billplz`.

## 2.0.1

### Changes

* Allows to install `jomweb/ringgit` `v2.0+`.

### Deprecated

* Deprecate `Billplz\Base\Bill\Transaction::show()`, use `get()` instead.

## 2.0.0

Released: 2018-04-26

### Added

* Added `Billplz\Base\OpenCollection` which can be accessed via `$client->openCollection()`.

### Changes

* Bump minimum stability to PHP 7.1 and above, and introduce scalar typehint and scalar return type.
* Bump `laravie/codex` to `v3.0.+`.
* Use `jomweb/ringgit` instead of `money/money` to parse money with Malaysia currency.

### Removed

* Remove `Billplz\Base\Collection::index()`, use `all()` instead.
* Remove `Billplz\Base\Bill::show()`, use `get()` instead.
* Remove handling Open Collection using `Billplz\Base\Collection`, use `Billplz\Base\OpenCollection` instead. You can also use `$client->openCollection()` helper.
* Remove `nesbot/carbon` suggested dependency.
