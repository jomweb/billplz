# Billplz v6.0.0

## Breaking Changes

1. Minimum supported PHP is now `8.3`
2. Response money values now hydrate to `\Money\Money`
3. `jomweb/ringgit`, `laravie/codex`, and `laravie/codex-filter` are no longer package dependencies

## Highlights

1. PHP `8.3+`
2. `\Money\Money` response values
3. Bundled Codex internals
4. Updated CI, static analysis, and tests

## Added

1. PHP 8.4 and 8.5 CI coverage
2. Payment Order coverage on the 6.x line
3. Local QA scripts for lint, analysis, and tests

## Changed

1. Minimum supported PHP is now `8.3`
2. Tests now run on Pest v4
3. CI now runs Pint, PHPStan, and Pest for the 6.x branch

## Removed

1. Hard dependency on `jomweb/ringgit`
2. External `laravie/codex` dependency chain
3. Support for PHP versions below `8.3`

## Upgrade Notes

1. Replace `\Duit\MYR` assumptions with `\Money\Money`
2. Keep using integer minor units if that fits your integration
3. Install a PHP-HTTP client implementation such as `php-http/guzzle7-adapter`
