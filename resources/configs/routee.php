<?php

return [
    /**
     * Set current application FQDN URL
     */
    'origin' => env('ROUTE_ORIGIN'),
    
    /**
     * List all the routee.json FQDN URL to be shared.
     */
    'source' => env('ROUTE_SOURCE', [])
];
