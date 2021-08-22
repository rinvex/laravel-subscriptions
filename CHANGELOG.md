# Rinvex Subscriptions Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


## [v6.0.0] - 2021-08-22
- Drop PHP v7 support, and upgrade rinvex package dependencies to next major version
- Update composer dependencies
- Merge rules instead of resetting, to allow adequate model override
- Fix constructor initialization order (fill attributes should come next after merging fillables & rules)
- Drop old MySQL versions support that doesn't support json columns
- Upgrade to GitHub-native Dependabot

## [v5.0.3] - 2021-03-15
- Changes in doc to reflect new ofSubscriber breaking change
- Utilize `SoftDeletes` functionality (fix #142)
- Update hardcoded model to use service container IoC
- Add period regardless if it's 0 or more, this should be fine
- Check if there's usage or not (fix #26 & #138)

## [v5.0.2] - 2021-02-19
- Define morphMany parameters explicitly
- Simplify service provider model registration into IoC
- Add startDate optional parameter to new subscription creation (fix #79)
- Fix FeatureSlug confused with FeatureName by mistake (fix #43 #48 #62 #65 #136 #137)
- Breaking Change: Rename "User" to "Subscriber" for more generic naming convention (fix #63)

## [v5.0.1] - 2020-12-25
- Add support for PHP v8

## [v5.0.0] - 2020-12-22
- Upgrade to Laravel v8
- Update validation rules

## [v4.1.0] - 2020-06-15
- Update validation rules
- Drop using rinvex/laravel-cacheable from core packages for more flexibility
  - Caching should be handled on the application layer, not enforced from the core packages
- Drop PHP 7.2 & 7.3 support from travis

## [v4.0.6] - 2020-05-30
- Remove default indent size config
- Add strip_tags validation rule to string fields
- Specify events queue
- Explicitly specify relationship attributes
- Add strip_tags validation rule
- Explicitly define relationship name

## [v4.0.5] - 2020-04-12
- Fix ServiceProvider registerCommands method compatibility

## [v4.0.4] - 2020-04-09
- Tweak artisan command registration
- Reverse commit "Convert database int fields into bigInteger"
- Refactor publish command and allow multiple resource values

## [v4.0.3] - 2020-04-04
- Fix namespace issue

## [v4.0.2] - 2020-04-04
- Enforce consistent artisan command tag namespacing
- Enforce consistent package namespace
- Drop laravel/helpers usage as it's no longer used

## [v4.0.1] - 2020-03-20
- Convert into bigInteger database fields
- Add shortcut -f (force) for artisan publish commands
- Fix migrations path

## [v4.0.0] - 2020-03-15
- Upgrade to Laravel v7.1.x & PHP v7.4.x

## [v3.0.2] - 2020-03-13
- Tweak TravisCI config
- Add migrations autoload option to the package
- Tweak service provider `publishesResources`
- Remove indirect composer dependency
- Drop using global helpers
- Update StyleCI config

## [v3.0.1] - 2019-12-18
- Fix `migrate:reset` args as it doesn't accept --step

## [v3.0.0] - 2019-09-23
- Upgrade to Laravel v6 and update dependencies

## [v2.1.1] - 2019-06-03
- Enforce latest composer package versions

## [v2.1.0] - 2019-06-02
- Update composer deps
- Drop PHP 7.1 travis test
- Refactor migrations and artisan commands, and tweak service provider publishes functionality
- Fix wrong container binding:
  - app('rinvex.subscriptions.plan_features') => app('rinvex.subscriptions.plan_feature')
  - app('rinvex.subscriptions.plan_subscriptions') => app('rinvex.subscriptions.plan_subscription')

## [v2.0.0] - 2019-03-03
- Require PHP 7.2 & Laravel 5.8

## [v1.0.2] - 2018-12-30
- Rinvex\Subscriptions\Services\Period: adding interval received as parameter in constructor to property ->interval

## [v1.0.1] - 2018-12-22
- Update composer dependencies
- Add PHP 7.3 support to travis
- Fix MySQL / PostgreSQL json column compatibility

## [v1.0.0] - 2018-10-01
- Enforce Consistency
- Support Laravel 5.7+
- Rename package to rinvex/laravel-subscriptions

## [v0.0.4] - 2018-09-21
- Update travis php versions
- Define polymorphic relationship parameters explicitly
- Fix fully qualified booking unit methods (fix #20)
- Convert timestamps into datetime fields and add timezone
- Tweak validation rules
- Drop StyleCI multi-language support (paid feature now!)
- Update composer dependencies
- Prepare and tweak testing configuration
- Update StyleCI options
- Update PHPUnit options
- Rename subscription model activation and deactivation methods

## [v0.0.3] - 2018-02-18
- Add PublishCommand to artisan
- Move slug auto generation to the custom HasSlug trait
- Add Rollback Console Command
- Add missing composer dependencies
- Remove useless scopes
- Add PHPUnitPrettyResultPrinter
- Use Carbon global helper
- Update composer dependencies
- Update supplementary files
- Use ->getKey() method instead of ->id
- Typehint method returns
- Drop useless model contracts (models already swappable through IoC)
- Add Laravel v5.6 support
- Simplify IoC binding
- Add force option to artisan commands
- Refactor user_id to a polymorphic relation
- Rename PlanSubscriber trait to HasSubscriptions
- Rename polymorphic relation customer to user
- Rename polymorphic relation customer to user
- Convert interval column data type into string from character

## [v0.0.2] - 2017-09-08
- Fix many issues and apply many enhancements
- Rename package rinvex/laravel-subscriptions from rinvex/subscribable

## v0.0.1 - 2017-06-29
- Tag first release

[v6.0.0]: https://github.com/rinvex/laravel-subscriptions/compare/v5.0.3...v6.0.0
[v5.0.3]: https://github.com/rinvex/laravel-subscriptions/compare/v5.0.2...v5.0.3
[v5.0.2]: https://github.com/rinvex/laravel-subscriptions/compare/v5.0.1...v5.0.2
[v5.0.1]: https://github.com/rinvex/laravel-subscriptions/compare/v5.0.0...v5.0.1
[v5.0.0]: https://github.com/rinvex/laravel-subscriptions/compare/v4.1.0...v5.0.0
[v4.1.0]: https://github.com/rinvex/laravel-subscriptions/compare/v4.0.6...v4.1.0
[v4.0.6]: https://github.com/rinvex/laravel-subscriptions/compare/v4.0.5...v4.0.6
[v4.0.5]: https://github.com/rinvex/laravel-subscriptions/compare/v4.0.4...v4.0.5
[v4.0.4]: https://github.com/rinvex/laravel-subscriptions/compare/v4.0.3...v4.0.4
[v4.0.3]: https://github.com/rinvex/laravel-subscriptions/compare/v4.0.2...v4.0.3
[v4.0.2]: https://github.com/rinvex/laravel-subscriptions/compare/v4.0.1...v4.0.2
[v4.0.1]: https://github.com/rinvex/laravel-subscriptions/compare/v4.0.0...v4.0.1
[v4.0.0]: https://github.com/rinvex/laravel-subscriptions/compare/v3.0.2...v4.0.0
[v3.0.2]: https://github.com/rinvex/laravel-subscriptions/compare/v3.0.1...v3.0.2
[v3.0.1]: https://github.com/rinvex/laravel-subscriptions/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/rinvex/laravel-subscriptions/compare/v2.1.1...v3.0.0
[v2.1.1]: https://github.com/rinvex/laravel-subscriptions/compare/v2.1.0...v2.1.1
[v2.1.0]: https://github.com/rinvex/laravel-subscriptions/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/rinvex/laravel-subscriptions/compare/v1.0.2...v2.0.0
[v1.0.2]: https://github.com/rinvex/laravel-subscriptions/compare/v1.0.1...v1.0.2
[v1.0.1]: https://github.com/rinvex/laravel-subscriptions/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/rinvex/laravel-subscriptions/compare/v0.0.4...v1.0.0
[v0.0.4]: https://github.com/rinvex/laravel-subscriptions/compare/v0.0.3...v0.0.4
[v0.0.3]: https://github.com/rinvex/laravel-subscriptions/compare/v0.0.2...v0.0.3
[v0.0.2]: https://github.com/rinvex/laravel-subscriptions/compare/v0.0.1...v0.0.2
