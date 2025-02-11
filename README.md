
# Version Management Command

This package provides a set of Artisan commands for managing version numbers in your Laravel project, particularly for handling the `APP_VERSION` in your `.env` file.

## Installation

1. Install the package via Composer:

```bash
  composer require cre8it/vincrease
```

2. (Optional) Publish the configuration file if you need to modify settings:

```bash
  php artisan vendor:publish --provider="Cre8it\Vincrease\VincreaseServiceProvider"
```

## Available Commands

### `version:init`

Initializes the `APP_VERSION` in your `.env` file if it does not exist. You can provide a custom version with the `--app-version` option.

#### Options:
- `--app-version`: The version number to set (e.g., `0.1.4`). If not provided, defaults to `1.0.0`.

#### Usage:

```bash
  php artisan version:init
```

To specify a version:

```bash
  php artisan version:init --app-version=2.0.0
```

### `version:increase`

Increases the version number in `APP_VERSION` in your `.env` file. By default, it increments the patch version, but you can specify the level of increment (major, minor, patch).

#### Usage:

```bash
  php artisan version:increase
```

## Testing

## Running Tests and Code Quality Tools

This project includes a few commands to help with code quality and testing:

- **Refactor**: Runs Rector to refactor the codebase.
- **Lint**: Runs Pint to lint the codebase for style issues.
- **Test**: Runs a set of tests with the following steps:
   - Refactor (with `--dry-run` to check refactorable issues)
   - Run pint to check linting
   - Run type checks with PHPStan at level 7
   - Run unit tests using Pest with code coverage, parallel execution, and a minimum coverage of 100%

### Available Composer Commands:

- **Fix**: Run all refactoring and linting tasks:
```bash
  composer fix
```

- **Test**: Run all testing tasks (refactor, lint, types, and unit tests):

```bash
  composer test
```

## License

This package is open-source and available under the MIT License.
