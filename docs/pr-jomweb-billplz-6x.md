# PR Draft For `jomweb/billplz`

## Title

`Release Billplz 6.0 with Money objects, bundled Codex internals, and updated CI`

## Body

### Summary

1. Prepare the `6.x` line for Billplz 6.0.0
2. Replace old money assumptions with `\Money\Money`
3. Bundle Codex internals in-package
4. Move CI and tests to the current toolchain

### Changes

1. Raise minimum PHP to `8.3`
2. Remove the hard dependency on `jomweb/ringgit`
3. Remove external `laravie/codex` and `laravie/codex-filter` dependencies
4. Migrate tests to Pest v4
5. Add PHPStan and Pint checks for the `6.x` line
6. Refresh README, changelog, and release docs

### Upgrade Notes

1. Response money values now hydrate to `\Money\Money`
2. Request payloads can use `\Money\Money::MYR(...)` or integer minor units
3. Existing `\Duit\MYR` integrations should convert from the returned Money amount

### Verification

1. `composer validate --strict`
2. `composer run qa`
3. GitHub Actions:
   - tests
   - analyse
   - coveralls
