# Changelog

## v3.3.0

### Added

- Add `PrimaryKeyColumnToIDAliasRector` to access model identity through the `id` alias instead of the primary key column name https://github.com/mll-lab/rector-config/pull/7

### Fixed

- Tolerate Rector 2.x releases without `FirstClassCallableRector` https://github.com/mll-lab/rector-config/pull/8

## v3.2.0

### Added

- Add `IfThrowToCoalesceThrowRector` to transform null-check-then-throw patterns to use the null coalesce operator with throw expression https://github.com/mll-lab/rector-config/pull/6
- Add `ElvisToCoalesceRector` to convert elvis operator (`?:`) to null coalesce (`??`) when the expression can only be falsy via null https://github.com/mll-lab/rector-config/pull/6

### Changed

- Select `FirstClassCallableRector` or `ArrayToFirstClassCallableRector` based on Rector version https://github.com/mll-lab/rector-config/pull/6

### Fixed

- Rector 1.x compatibility for `IfThrowToCoalesceThrowRector` https://github.com/mll-lab/rector-config/pull/6

## v3.1.1

### Removed

- Remove enforcing usage of `\Illuminate\Support\Str` over `\Str` in favor of https://github.com/driftingly/rector-laravel/blob/main/config/sets/laravel-facade-aliases-to-full-names.php

## v3.1.0

### Added

- Enforce usage of `\Illuminate\Support\Str` over `\Str`

## v3.0.0

### Added

- Convert `Collection::make()` to `new Collection()`

### Changed

- Require `rector/rector` versions `^1.2.7 || ^2.0.10`
- Require PHP 8.3

## v2.5.0

### Added

- Allow rector v2

## v2.4.0

### Added

- Allow rector v1

## v2.3.0

### Removed

- Remove `FinalizeClassesWithoutChildrenRector`

## v2.2.0

### Added

- Allow rector `0.15 - 0.19`

## v2.1.0

### Added

- Add Laravel rule to convert `collect()` to `new Illuminate\Support\Collection()`

## v2.0.1

### Fixed

- Update rector to `^0.15`

## v2.0.0

### Added

- Add `FirstClassCallableRector`

### Changed

- Require PHP 8.1

## v1.0.0

### Added

- Add basic rector config
