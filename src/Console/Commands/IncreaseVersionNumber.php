<?php

namespace Cre8it\Vincrease\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class IncreaseVersionNumber extends Command
{
    protected $signature = 'vincrease:up {--type=}';

    protected $description = 'Increment the APP_VERSION value in the .env file';

    public function handle(): int
    {
        $path = base_path('.env');

        if (! File::exists($path)) {
            $this->error('.env file not found!');

            return 1;
        }
        $envContents = File::get($path);

        preg_match('/APP_VERSION="(\d+)\.(\d+)\.(\d+)"/', $envContents, $matches);

        if (! $matches) {
            $this->error('APP_VERSION not found or invalid format.');

            return 1;
        }

        [$fullMatch, $major, $minor, $patch] = $matches;
        $newVersion = $this->increaseVersionNumber((int) $major, (int) $minor, (int) $patch);

        $updatedContents = str_replace($fullMatch, "APP_VERSION=\"$newVersion\"", $envContents);

        File::put($path, $updatedContents);

        $this->info("APP_VERSION updated to $newVersion");

        return 0;
    }

    private function increaseVersionNumber(int $major, int $minor, int $patch): string
    {
        return match (true) {
            $this->isMajor() => (++$major).'.0.0',
            $this->isMinor() => $major.'.'.(++$minor).'.0',
            $this->isPatch() => $major.'.'.$minor.'.'.(++$patch),
            default => $major.'.'.$minor.'.'.(++$patch),
        };
    }

    private function isMajor(): bool
    {
        return $this->containsAny($this->getType(), ['maj', 'major']);
    }

    private function isMinor(): bool
    {
        return $this->containsAny($this->getType(), ['min', 'minor']);
    }

    private function isPatch(): bool
    {
        return $this->containsAny($this->getType(), ['pat', 'patch']);
    }

    private function getType(): string
    {
        $opt = $this->option('type') ?? '';
        assert(is_string($opt));

        return strtolower(trim($opt));
    }

    /**
     * @param  array<string>  $needles
     */
    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}
