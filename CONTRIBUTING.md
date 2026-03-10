# Contributing

Contributions are welcome, and are accepted via pull requests. Please review these guidelines before submitting any pull requests.

## Guidelines

* Please follow the [PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).
* Ensure that the current tests pass, and if you've added something new, add the tests where relevant.
* Remember that we follow [SemVer](http://semver.org). If you are changing the behaviour, or the public api, you may need to update the docs.
* Send a coherent commit history, making sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash](http://git-scm.com/book/en/Git-Tools-Rewriting-History) them before submitting.
* You may also need to [rebase](http://git-scm.com/book/en/Git-Branching-Rebasing) to avoid merge conflicts.

## Running Tests

You will need an install of [Composer](https://getcomposer.org) before continuing.

First, install the dependencies:

```bash
$ composer install
```

Then install the repo-managed git hooks:

```bash
$ composer hooks:install
```

The hooks run:

* `pre-commit`: `composer lint`
* `pre-push`: `composer analyse` and `composer test`

You can also run the checks manually:

```bash
$ composer lint
$ composer analyse
$ composer test
$ composer qa
```

If the test suite passes on your local machine you should be good to go.

When you make a pull request, the checks will automatically run again on GitHub Actions.

## Support Policy

For general releases, bug fixes are provided for 6 months and security fixes are provided for 1 year after the next major version has been released.

| Branch | Bug Fixes Until | Security Fixes Until
|:-------|:----------------|:--------------------
| `3.x`  | 6th July 2020   | 6th January 2021
