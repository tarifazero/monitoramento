<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('percentage', function ($value) {
            return <<<PHP
                <?php
                \$number_formatter = new \\NumberFormatter(config('app.locale'), \\NumberFormatter::PERCENT);
                echo \$number_formatter->format($value);
                ?>
PHP;
        });
    }
}
