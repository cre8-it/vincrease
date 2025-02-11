<?php

namespace Cre8it\Vincrease\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InitVersionNumber extends Command
{
    protected $signature = 'vincrease:init {--app-version=}';

    protected $description = 'Init version number';

    public function handle(): int
    {
        $path = base_path('.env');

        if (! File::exists($path)) {
            $this->error('.env file not found!');

            return 1;
        }
        $envContents = File::get($path);

        preg_match('/APP_VERSION="(\d+)\.(\d+)\.(\d+)"/', $envContents, $matches);

        if ($matches) {
            [$fullMatch] = $matches;
            $this->error('APP_VERSION already exists: '.$fullMatch);

            return 1;
        }

        if ($opt = $this->option('app-version')) {
            assert(is_string($opt));

            $isValid = $this->validateVersion($opt);

            if (! $isValid) {
                $this->error('Invalid version: '.$opt);

                return 1;
            }
            $version = $opt;
        }
        $version ??= '1.0.0';

        File::append($path, PHP_EOL.'APP_VERSION="'.$version.'"'.PHP_EOL);

        $this->info('APP_VERSION initialized to '.$version);

        return 0;
    }

    private function validateVersion(string $version): bool
    {
        return (bool) preg_match('/^\d+\.\d+\.\d+$/', $version);
    }
}
