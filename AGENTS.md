# Repository Guidelines

## Project Structure & Module Organization
This repository is currently a newly baked CakePHP 5 application. Runtime code lives in `src/`, templates in `templates/`, public assets in `webroot/`, test code in `tests/`, and framework/bootstrap config in `config/`. Routing is defined in `config/routes.php`: `/` mounts `PagesController::display('home')`, `/pages/*` maps to `PagesController::display`, and fallback routes are enabled for the standard controller/action/id patterns. Product planning lives in `project-docs/overview.md`, `project-docs/auth-architecture.md`, and `project-docs/roadmap.md`. The current architecture direction is a four-plugin split around `Appwrite`, `AppwriteUsers`, `AppwriteAuthentication`, and a reusable local `UserAccess` layer.

## Build, Test, and Development Commands
Install dependencies with `composer install`. Start the local CakePHP server with `bin/cake server -p 8765`. Run the full test suite with `composer test`. Run a single test file with `vendor/bin/phpunit tests/TestCase/ApplicationTest.php`. Check coding standards with `composer cs-check` and auto-fix standard sniff violations with `composer cs-fix`.

## Coding Style & Naming Conventions
Follow `.editorconfig`: 4-space indentation, LF line endings, final newline enabled, and trailing whitespace trimmed. YAML files use 2 spaces, and `.neon` files use tabs. `phpcs.xml` applies the CakePHP coding standard to `src/` and `tests/`; controllers are explicitly exempted from the missing native return type sniff. Existing application classes use `declare(strict_types=1);`, PSR-4 autoloading under `App\\`, and CakePHP's standard class placement (`Controller`, `View`, `Console`, etc.).

## Testing Guidelines
PHPUnit is configured through `phpunit.xml.dist` and boots from `tests/bootstrap.php`. Add or update tests under `tests/TestCase/`. Coverage is scoped to `src/` and `plugins/*/src/`, with `src/Console/Installer.php` excluded. No repo-specific coverage threshold is configured yet.

## Commit & Pull Request Guidelines
Git history is minimal; the only current commit uses a Conventional Commit-style prefix: `chore: freshly baked CakePHP App`. Until the project establishes a broader pattern, keep commit subjects short and prefix-based. The existing `.github/PULL_REQUEST_TEMPLATE.md` is the default CakePHP skeleton template and asks for the big-picture change summary, linked issues, passing tests, and tests for new behavior.
