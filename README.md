
# Version Management Command

This package provides a set of Artisan commands for managing version numbers in your Laravel project, particularly for handling the `APP_VERSION` in your `.env` file. It is designed for continuous deployments and follows semantic versioning to keep track of application updates.

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

### `vincrease:init`

Initializes the `APP_VERSION` in your `.env` file if it does not exist. You can provide a custom version with the `--app-version` option.

#### Options:
- `--app-version`: The version number to set (e.g., `0.1.4`). If not provided, defaults to `1.0.0`.

#### Usage:

```bash
  php artisan vincrease:init
```

To specify a version:

```bash
  php artisan vincrease:init --app-version=2.0.0
```

### `vincrease:up`

Increases the version number in `APP_VERSION` in your `.env` file. By default, it increments the patch version, but you can specify the level of increment (major, minor, patch).

#### Usage:

By default, increase the patch version:
```bash
  php artisan vincrease:up
```
```
  APP_VERSION updated to 3.0.1
```

To increase the major version:
```bash
  php artisan vincrease:up --type=major
```
```
APP_VERSION updated to 4.0.0
```


## Integrations


Services like Ploi and Forge are just two examples of how it can be integrated, but similar functionality can be achieved with other deployment providers. Refer to their documentation for details on how to pass versioning parameters or modify deployment scripts accordingly.

### Using `vincrease` with Ploi

If you're using [Ploi.io](https://ploi.io) for deployment, you can integrate `vincrease` into your deployment script to ensure the `APP_VERSION` is incremented with each deploy, depending on the type of version increase you hint in your latest commit message for example.

Example deployment script:

```bash
    cd /home/ploi/example.com
    git pull origin main
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

    php artisan vincrease:up --type="{COMMIT_MESSAGE}"

    php artisan config:clear
    php artisan cache:clear
    php artisan migrate --force
```

For [Laravel Forge](https://forge.laravel.com), you can modify your deployment script to include `vincrease`, ensuring that each deployment updates the application version, based on environment variables injected into your deployment script by Forge.

Example deployment script:

```bash
cd /home/forge/example.com
git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader

php artisan vincrease:up --type="$FORGE_DEPLOY_MESSAGE"

php artisan config:clear
php artisan cache:clear
php artisan migrate --force
```

Additionally, Forge allows passing query parameters to deployment triggers. This means you can dynamically control version increments by passing `type=major` or `type=minor` in the trigger URL:

```
https://forge.laravel.com/some-trigger-url?token={your_token}&type=major
```

Forge will then inject a `FORGE_VAR_TYPE` variable that can be used in your deployment script:

```bash
php artisan vincrease:up --type="$FORGE_VAR_TYPE"
```


**Why use `vincrease`?**

automatically updating an `APP_VERSION` might be useful to:
- Pass the version in response headers to enable cache and asset invalidation on the client-side after each deployment.
- Track and log deployments, making it easier to correlate issues with specific releases.
- Use the version number in API responses for debugging and version control in client applications.
- Ensure version consistency across distributed systems or microservices by syncing version numbers.
- Integrate with monitoring tools to tag logs and error reports with the current application version.  
  
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
