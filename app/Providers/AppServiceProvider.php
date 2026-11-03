<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(fn () => Password::min(8)
            ->mixedCase()
            ->letters()
            ->numbers()
            ->symbols());

        Carbon::macro('toSystemDate', function (): string {
            /** @var Carbon $this */
            return $this->format('d/m/Y');
        });

        Carbon::macro('toSystemDateTime', function (): string {
            /** @var Carbon $this */
            return $this->format('d/m/Y H:i');
        });

        Blade::directive('date', function (string $expression): string {
            return "<?php echo ($expression) ? (($expression) instanceof \DateTimeInterface ? ($expression)->format('d/m/Y') : \Illuminate\Support\Carbon::parse($expression)->format('d/m/Y')) : ''; ?>";
        });

        Blade::directive('datetime', function (string $expression): string {
            return "<?php echo ($expression) ? (($expression) instanceof \DateTimeInterface ? ($expression)->format('d/m/Y H:i') : \Illuminate\Support\Carbon::parse($expression)->format('d/m/Y H:i')) : ''; ?>";
        });
    }
}
