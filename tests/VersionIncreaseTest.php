<?php

use Illuminate\Support\Facades\File;

test('it fails if the .env file is missing', function (): void {
    File::shouldReceive('exists')->once()->with(base_path('.env'))->andReturn(false);

    $this->artisan('vincrease:up')
        ->expectsOutput('.env file not found!')
        ->assertExitCode(1);
});

describe('for tests with a valid .env file', function (): void {
    beforeEach(function (): void {
        File::shouldReceive('exists')->with(base_path('.env'))->andReturn(true);
    });

    test('it does not increment if APP_VERSION is missing', function (): void {
        File::shouldReceive('get')->with(base_path('.env'))->andReturn(<<<'ENV'
            APP_NAME="Laravel"
        ENV
        );

        $this->artisan('vincrease:up')
            ->expectsOutput('APP_VERSION not found or invalid format.')
            ->assertExitCode(1);
    });

    test('it increments the major version', function (): void {
        File::shouldReceive('get')->with(base_path('.env'))->andReturn(<<<'ENV'
            APP_VERSION="2.3.4"
        ENV
        );

        File::shouldReceive('put')->withArgs(fn ($path, $content) => str_contains($content, 'APP_VERSION="3.0.0"'));

        $this->artisan('vincrease:up --type=major')
            ->expectsOutput('APP_VERSION updated to 3.0.0')
            ->assertExitCode(0);
    });

    test('it increments the minor version', function (): void {
        File::shouldReceive('get')->once()->with(base_path('.env'))->andReturn(<<<'ENV'
            APP_VERSION="2.3.4"
        ENV
        );

        File::shouldReceive('put')->withArgs(fn ($path, $content) => str_contains($content, 'APP_VERSION="2.4.0"'));

        $this->artisan('vincrease:up --type=minor')
            ->expectsOutput('APP_VERSION updated to 2.4.0')
            ->assertExitCode(0);
    });

    test('it increments the patch version (default)', function (): void {
        File::shouldReceive('get')->once()->with(base_path('.env'))->andReturn(<<<'ENV'
            APP_VERSION="2.3.4" 
        ENV
        );

        File::shouldReceive('put')
            ->once()
            ->withArgs(fn ($path, $content) => str_contains($content, 'APP_VERSION="2.3.5"'))
            ->andReturnTrue();

        $this->artisan('vincrease:up')
            ->expectsOutput('APP_VERSION updated to 2.3.5')
            ->assertExitCode(0);
    });
});
