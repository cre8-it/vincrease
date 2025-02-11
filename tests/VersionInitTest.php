<?php

use Illuminate\Support\Facades\File;

test('it fails if the .env file is missing', function (): void {
    File::shouldReceive('exists')->once()->with(base_path('.env'))->andReturn(false)->byDefault();

    $this->artisan('version:init')
        ->expectsOutput('.env file not found!')
        ->assertExitCode(1);
});

describe('for tests with a valid .env file', function (): void {
    beforeEach(function (): void {
        File::shouldReceive('exists')->with(base_path('.env'))->andReturn(true);
    });

    test('it does not overwrite existing APP_VERSION', function (): void {
        File::shouldReceive('get')->with(base_path('.env'))->andReturn(<<<'ENV'
            APP_NAME="Laravel"
            APP_VERSION="2.3.4"
        ENV);

        $this->artisan('version:init')
            ->expectsOutput('APP_VERSION already exists: APP_VERSION="2.3.4"')
            ->assertExitCode(1);
    });

    test('it rejects an invalid app version', function (): void {
        File::shouldReceive('get')->once()->with(base_path('.env'))->andReturn('');

        $this->artisan('version:init', ['--app-version' => 'invalid-version'])
            ->expectsOutput('Invalid version: invalid-version')
            ->assertExitCode(1);
    });

    test('it initializes APP_VERSION if not present', function (): void {
        File::shouldReceive('get')->with(base_path('.env'))->andReturn(<<<'ENV'
            APP_NAME="Laravel"
        ENV);

        File::shouldReceive('put')->withArgs(fn ($path, $content) => str_contains($content, 'APP_VERSION="1.0.0"'));

        File::shouldReceive('append')->once()->with(base_path('.env'), PHP_EOL.'APP_VERSION="1.0.0"'.PHP_EOL);

        $this->artisan('version:init')
            ->expectsOutput('APP_VERSION initialized to 1.0.0')
            ->assertExitCode(0);
    });

    test('it sets the provided app version', function (): void {
        File::shouldReceive('get')
            ->once()
            ->with(base_path('.env'))
            ->andReturn('');

        File::shouldReceive('append')
            ->once()
            ->withArgs(function ($path, $content) {
                dump($content);

                return str_contains($content, 'APP_VERSION="1.2.3"');
            })
            ->andReturnTrue();

        $this->artisan('version:init', ['--app-version' => '1.2.3'])
            ->expectsOutput('APP_VERSION initialized to 1.2.3')
            ->assertExitCode(0);
    });
});
