# Changelog for v6.x

This changelog references the relevant changes done to `jomweb/billplz`.

## 6.0.0

Released: TBD

### Added

* Added support for PHP 8.4 and PHP 8.5 in CI.
* Added `PaymentOrderCollection` and `PaymentOrder` coverage on the 6.x line.
* Added local QA scripts for lint, static analysis, and tests.

### Changed

* Bumped the minimum supported PHP version to `8.3`.
* Migrated the test suite to Pest v4.
* Response money values now hydrate to `\Money\Money`.
* Bundled Codex request, response, and filter internals into the package.
* Updated CI to run tests, Pint, and PHPStan on the 6.x branch.

### Removed

* Removed the hard dependency on `jomweb/ringgit`.
* Removed external `laravie/codex` and `laravie/codex-filter` package dependencies.
* Removed support for PHP versions below `8.3`.

### Internal

* Refreshed contributor docs and local git hook guidance.
* Updated release-facing package documentation for Billplz 6.

### Upgrade Notes

* Replace `\Duit\MYR` assumptions with `\Money\Money`.
* Keep using integer minor units if that is a better fit for your integration.
* Install a PHP-HTTP client implementation such as `php-http/guzzle7-adapter`.
