<?php
namespace Lokalkoder\Routee;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Storage;

class Routee
{
    /**
     * Routee config
     *
     * @var array
     */
    protected $config;
    
    /**
     * Route list
     *
     * @var Collection
     */
    protected $routeList;

    public function __construct(RouteCollection $routes)
    {
        $this->config = Config::get('routee');

        $this->routeList = $routes;
    }

    /**
     * Format route collection parameters.
     *
     * @return Collection
     */
    public function map(): Collection
    {
        return collect($this->routeList->getRoutesByName())->filter(function ($item, $name) {
            return ($item instanceof \Illuminate\Routing\Route);
        })->mapWithKeys(function ($route) {
            return [
                $route->getName() => collect([
                    'uri' => $route->uri(),
                    'methods' => $route->methods(),
                    'domain' => $this->config['origin']
                ])
            ];
        });
    }

    /**
     * Create routee.json file
     *
     * @param Collection $routes
     *
     * @return string
     */
    public function saveAsJson(Collection $routes): string
    {
        $path = 'routee.json';

        $storage = Storage::disk('public');

        $storage->put($path, $routes->toJson(), 'public');

        $publicPath = public_path($path);

        copy(storage_path('app/public/'.$path), $publicPath);

        return asset($path);
    }
}
