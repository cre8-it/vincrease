<?php

namespace Cre8it\Vincrease\Console\Commands;

use Illuminate\Console\Command;

final class IncreaseVersionNumber extends Command
{
    protected $signature = 'version:increase {--type=}';

    protected $description = 'Increment the APP_VERSION value in the .env file';

    public function handle(): void
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            $this->error('.env file not found!');
            return;
        }
        $envContents = file_get_contents($path);

        preg_match('/APP_VERSION="(\d+)\.(\d+)\.(\d+)"/', $envContents, $matches);

        if (!$matches) {
            $this->error('APP_VERSION not found or invalid format.');

            return;
        }

        [$fullMatch, $major, $minor, $patch] = $matches;
        $newVersion = $this->increaseVersionNumber((int) $major, (int) $minor, (int) $patch);

        $updatedContents = str_replace($fullMatch, "APP_VERSION=\"$newVersion\"", $envContents);

        file_put_contents($path, $updatedContents);

        $this->info("APP_VERSION updated to $newVersion");
    }

    private function increaseVersionNumber(int $major, int $minor, int $patch): string
    {
        return match(true) {
            $this->isMajor() => (++$major).'.0.0',
            $this->isMinor() => $major.'.'.(++$minor).'.0',
            $this->isPatch() => $major.'.'.$minor.'.'.(++$patch),
            default => '0.0.'.(++$patch),
        };
    }

    private function isMajor(): bool
    {
        return $this->containsAny($this->getType(), ['maj', 'ma', 'major']);
    }

    private function isMinor(): bool
    {
        return $this->containsAny($this->getType(), ['min', 'mi', 'minor']);
    }

    private function isPatch(): bool
    {
        return $this->containsAny($this->getType(), ['pat', 'pa', 'patch']);
    }

    private function getType(): string
    {
        return strtolower(trim($this->option('type') ?? ''));
    }


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
