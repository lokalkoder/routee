<?php
namespace Lokalkoder\Routee;

use Illuminate\Support\ServiceProvider;
use Lokalkoder\Routee\Consoles\RouteeConsole;
use Lokalkoder\Routee\Consoles\RouteZiggyConsole;

class RouteeProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $resources = dirname(__DIR__).'/resources/';

        $this->publishes([
            $resources.'configs/routee.php' => config_path('routee.php'),
        ], 'config');

        $this->consoleBoot();
    }

    protected function consoleBoot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RouteeConsole::class,
                RouteZiggyConsole::class
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
