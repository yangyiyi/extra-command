# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.4] - 2021-07-21
- Added new support options `-a` for `ServiceMakeCommand` and `FacadeMakeCommand` to create `service` (for `FacadeMakeCommand`) \ `facade` (for `ServiceMakeCommand`), `model` and `migration` at the same time.
- Update readme.

## [1.0.3] - 2021-07-15
- Update readme and license.

## [1.0.2] - 2021-07-07
- `ServiceMakeCommand` and `FacadeMakeCommand` handle function will call parent:handle() to create the file instead of copy everything.

## [1.0.1] - 2021-07-06
- fixed issue on the during call `make:facade` and `make:service` unable create model and migration at the same time.

## [1.0.0] - 2021-07-06
- add model and migration option during call `make:facade` and `make:service` under options `-m` for `model`, `-g` for `migration`.

## [0.0.2] - 2021-07-01
- Fixed make:facade with service not working
- Fixed make:facade will missing facade word.

## [0.0.1] - 2021-07-01
Initial commit and early project setup.