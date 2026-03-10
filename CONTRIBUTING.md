# Contributing

Contributions are welcome, and are accepted via pull requests. Please review these guidelines before submitting any pull requests.

## Guidelines

* Please follow the coding style enforced by [Laravel Pint](https://laravel.com/docs/12.x/pint) and run `composer lint` before committing.
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

## Support

This package is provided as open source software without any guaranteed support or service level agreement.

Bug fixes, maintenance updates, and new releases are provided at the maintainers' discretion.

If you discover a security issue, please report it responsibly through the appropriate private channel instead of opening a public issue.
