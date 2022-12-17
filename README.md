# DB Service

A thin layer on top of `doctrine/dbal`.

## Getting started

## Prerequisites

* php: `~8.0||~8.1||~8.2`
* Composer `^2.3` (prefered)

### Installation

```sh
composer require jascha030/db
```

### Testing

Included with the package are a set of Unit tests using `phpunit/phpunit`. For ease of use a composer script command is
defined to run the tests.

The default configuration will be used when using the `test` command, which is defined at `.phpunit.xml`.

```sh
composer test
# Or
composer run test
```

A code coverage report is generated in the project's root as `cov.xml`. The `cov.xml` file is not ignored in the
`.gitignore` by default. You are encouraged to commit the latest code coverage report, when deploying new features.

### Code style & Formatting

A code style configuration for `friendsofphp/php-cs-fixer` is included, defined in `.php-cs-fixer.php`.

```sh
composer format
```

## License

This composer package is an open-sourced software licensed under
the [MIT License](https://github.com/jascha030/sb-service/blob/master/LICENSE.md)
