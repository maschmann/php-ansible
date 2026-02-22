# Changelog

## Unreleased
 * Updated API compatibility to modern Ansible (e.g., explicitly prepending 'role' to ansible-galaxy commands).
 * Introduced `AnsibleGalaxyCollectionInterface` for full `ansible-galaxy collection` support (init, build, publish, install).
 * Resolved `Symfony\Process` argument passing issues to ensure reliable escaping of options.
 * Modernized `ansible-playbook` wrapper by dropping deprecated `--su` parameters and adding `--become-method`, `--become-password-file`, `--connection-password-file`, and `--list-tags`.
 * Refactored codebase for PHP 8.4 compatibility (e.g., explicitly nullable `LoggerInterface` parameter).
 * Fixed GitHub Actions workflow dependency resolution for PHP 8.2 and 8.3 by broadening `phpunit/phpunit` version constraints.
 * Upgraded Docker development environment to simultaneously support PHP 8.2, 8.3, and 8.4 via `compose.yaml` profiles/services.
 * Created robust DTOs (e.g., `ProcessResult`) for process output and exit code management.
 * Resolved over 30 static analysis and code style errors (`phpstan`, `phpcs`).
 * General library, static analysis tooling (PHPUnit 13), and test suite upgrades.

## v5.0.0
 * Wrapping each host into quotes instead of having double quotes around all hosts.
 * Fixed error output parsing (removed extra escaping).
 * Permitted JSON format strings for the `--extra-vars` parameter.

## v4.1.0
 * Allowed all psr/log versions to be used.
 * Feature/build and version cleanup.

## v4.0.0
 * drop php7.x compat, 8.0+ only
 * removed travis, scrutinizer, replace with github actions
 * add symfony process v6.x, drop process <5.x
 * fix differences main/dev >.<

## v3.0.x
 * switch to 7.3|8.0

## v2.0.0
 * migration to php >=7.1 only
 * move to ansible 2.x
 * general library upgrades
 * cleanup

## v1.0.1
 * Added domain specific exception

## v1.0
 * Enhanced TravisCI tests to include several versions of process component

## v0.2
 
 * configurable timeout for processes
 * refactored --extra-vars to take string or array parameters

## v0.1

 * added ansible-galaxy implementation and tests

## v0.0.2

 * small fixes to UnitTests
 * cleanup
 * linked license in README
 * fixed travis config


## v0.0.1
initial version

 * ansible commandline-wrapper (not feature-complete)
 * basic verification for ansible installation
 * min-version check
