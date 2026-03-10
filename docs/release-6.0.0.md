# Billplz 6.0.0 Release Checklist

1. Run `composer validate --strict`.
2. Run `composer run qa`.
3. Push `6.x` to `abdusfauzi/billplz`.
4. Confirm `jomweb/billplz` has an upstream `6.x` branch before opening a PR.
5. Open the PR with breaking changes, upgrade notes, and QA status.
6. Prepare GitHub release notes from `CHANGELOG-6.x.md`.
7. Create tag `v6.0.0` only after the upstream branch and release notes are ready.
8. Confirm Packagist can read the pushed tag from the canonical repository.
