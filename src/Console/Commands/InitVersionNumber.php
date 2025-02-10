<?php

namespace Cre8it\Vincrease\Console\Commands;

use Illuminate\Console\Command;

class InitVersionNumber extends Command
{
    protected $signature = 'version:init {--app-version=}';

    protected $description = 'Init version number';

    public function handle(): void
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            $this->error('.env file not found!');
            return;
        }
        $envContents = file_get_contents($path);

        preg_match('/APP_VERSION="(\d+)\.(\d+)\.(\d+)"/', $envContents, $matches);
        dump($matches);

        if ($matches) {
            [$fullMatch] = $matches;
            $this->error('APP_VERSION already exists: ' . $fullMatch);

            return;
        }

        if ($opt = $this->option('version')) {
            $isValid = $this->validateVersion($opt);
            if (!$isValid) {
                $this->error('Invalid version: ' . $opt);

                return;
            }
            $version = $opt;
        }
        $version = $version ?? '1.0.0';

        file_put_contents($path, PHP_EOL . 'APP_VERSION="'.$version.'"' . PHP_EOL, FILE_APPEND);

        $this->info('APP_VERSION initialized to 1.0.0');
    }


    private function validateVersion(string $version): bool
    {
        return (bool) preg_match('/^\d+\.\d+\.\d+$/', $version);
    }
}
