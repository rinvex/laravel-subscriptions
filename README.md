# Rinvex Subscriptions

**Rinvex Subscriptions** is a flexible plans and subscription management system for Laravel, with the required tools to run your SAAS like services efficiently. It's simple architecture, accompanied by powerful underlying to afford solid platform for your business.

[![Packagist](https://img.shields.io/packagist/v/rinvex/laravel-subscriptions.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/laravel-subscriptions)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/laravel-subscriptions.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/laravel-subscriptions/)
[![Travis](https://img.shields.io/travis/rinvex/laravel-subscriptions.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/laravel-subscriptions)
[![StyleCI](https://styleci.io/repos/93313402/shield)](https://styleci.io/repos/93313402)
[![License](https://img.shields.io/packagist/l/rinvex/laravel-subscriptions.svg?label=License&style=flat-square)](https://github.com/rinvex/laravel-subscriptions/blob/develop/LICENSE)


## Considerations

- Payments are out of scope for this package.
- You may want to extend some of the core models, in case you need to override the logic behind some helper methods like `renew()`, `cancel()` etc. E.g.: when cancelling a subscription you may want to also cancel the recurring payment attached.


## Installation

1. Install the package via composer:
    ```shell
    composer require mo7zayed/laravel-subscriptions
    ```

2. Publish resources (migrations and config files):
    ```shell
    php artisan rinvex:publish:subscriptions
    ```

3. Execute migrations via the following command:
    ```shell
    php artisan rinvex:migrate:subscriptions
    ```

4. Done!


## Docs
Please continue reading the docs on [https://github.com/rinvex/laravel-subscriptions](https://github.com/rinvex/laravel-subscriptions)
