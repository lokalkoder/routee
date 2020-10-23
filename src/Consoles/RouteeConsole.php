<?php
namespace Lokalkoder\Routee\Consoles;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lokalkoder\Routee\Routee;

class RouteeConsole extends Command
{
    protected $signature = 'routee';

    protected $description = 'Save the route';

    public function handle()
    {
        $this->setupCheck();
        
        $this->info('Getting All Routes');

        $routee = new Routee(Route::getRoutes());

        $this->info('Save json file.');

        $this->info('Json URL : ' . $routee->saveAsJson($routee->map()));
    }

    /**
     * Check the routee setup.
     *
     * @return void
     */
    protected function setupCheck()
    {
        if (($config = Config::get('routee')) === null) {
            $this->error('Routee configuration not yet set!!!');

            $this->call('vendor:publish', [
                '--provider' => "Lokalkoder\Routee\RouteeProvider",
                '--tag' => "config"
            ]);

            $this->error('Configuration file created for you.');
            exit;
        }

        if (Arr::has($config, 'origin') && Arr::get($config, 'origin') === null) {
            $this->error('Origin URL need to be set');
            exit;
        }
    }
}
