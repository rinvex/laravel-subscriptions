# Rinvex Subscriptions Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


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

[v1.0.0]: https://github.com/rinvex/laravel-subscriptions/compare/v0.0.4...v1.0.0
[v0.0.4]: https://github.com/rinvex/laravel-subscriptions/compare/v0.0.3...v0.0.4
[v0.0.3]: https://github.com/rinvex/laravel-subscriptions/compare/v0.0.2...v0.0.3
[v0.0.2]: https://github.com/rinvex/laravel-subscriptions/compare/v0.0.1...v0.0.2
