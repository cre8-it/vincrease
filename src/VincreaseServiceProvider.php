<?php

namespace Cre8it\Vincrease;

use Cre8it\Vincrease\Console\Commands\IncreaseVersionNumber;
use Cre8it\Vincrease\Console\Commands\InitVersionNumber;
use Illuminate\Support\ServiceProvider;

class VincreaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            IncreaseVersionNumber::class,
            InitVersionNumber::class,
        ]);
    }
}
