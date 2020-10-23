<?php
namespace Lokalkoder\Routee;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class RouteeSource
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

    public function __construct()
    {
        $this->config = Config::get('routee');

        $this->routeList = collect([]);
    }

    /**
     * Get route collection from URL
     *
     * @return Collection
     */
    public function routeCollection(): Collection
    {
        collect(explode(',', $this->config['source']))->each(function ($source) {
            $json = \json_decode(file_get_contents($source), true);

            $this->routeList = $this->routeList->merge($json);
        });
        
        return $this->routeList;
    }
}
