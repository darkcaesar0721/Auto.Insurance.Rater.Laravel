# Changelog

<!-- There is always Unreleased section on the top. Subsections (Added, Changed, Fixed, Removed) should be added as needed. -->

## Unreleased

## 3.1.1 - 2019-11-11
- Fix Gitlab 9.0+ support (environment variables were renamed in GitLab 9.0+).

## 3.1.0 - 2018-02-19
- Add continuousphp CI support.
- Add drone CI support.

## 3.0.0 - 2017-10-27
- Require PHP 7.1, use strict types.

## 2.1.0 - 2017-05-26
- Add AppVeyor (Windows cloud CI) support.

## 2.0.0 - 2017-01-07
- BC: The `detect()` method of `CiDetector` class is no longer static.
- BC: Rearrange namespaces, all classes are now in `CiDetector` sub-namespace.
- Added `isCiDetected()` method to detect if current environment is CI.
- BC: `detect()` method always returns instance of `CiInterface` and throws `CiNotDetectedException` if CI environment is not detected.

## 1.1.0 - 2017-01-06
- Add `getGitBranch()` method to detect Git branch of the build (supported by all CIs except TeamCity).
- Add `getRepositoryUrl()` method to detect repository source URL (not supported Codeship, TeamCity, Travis).

## 1.0.0 - 2016-08-20
- Initial release supporting Jenkins, Travis CI, Bamboo, CircleCI, Codeship, GitLab and TeamCity services.
