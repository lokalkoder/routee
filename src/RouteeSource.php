<?php
namespace Lokalkoder\Routee;

use Illuminate\Support\Arr;
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
        collect(explode(',', Arr::get($this->config, 'source', [])))->each(function ($url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $source = $url . '/routee.json';
                
                if (($fileSource = file_get_contents($source)) !== false) {
                    $json = \json_decode($fileSource, true);

                    if (json_last_error() === JSON_ERROR_NONE) {
                        $this->routeList = $this->routeList->merge($json);
                    }
                }
            }
        });
        
        return $this->routeList;
    }
}
