# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.4.0] - 2023-11-28

### Added

- Support for PHP 8.3 ([#14](https://github.com/khalyomede/php-typo/issues/14)).

## [0.3.0] - 2022-12-10

### Added

- Support for PHP 8.2 ([#6](https://github.com/khalyomede/php-typo/issues/6)).

## Breaking

- Dropped support for PHP 8.1 ([#6](https://github.com/khalyomede/php-typo/issues/6)).

## [0.2.0] - 2022-09-21

### Added

- Reduced false positives by ignoring numbers ([#1](https://github.com/khalyomede/php-typo/issues/1)).

### Fixed

- "__toString" will now be correctly understood as 2 words "to" and "string" instead of "tostring" ([#2](https://github.com/khalyomede/php-typo/issues/2)).

## [0.1.0] - 2022-08-21

### Added

- First working version.
