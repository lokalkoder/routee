<?php
namespace Lokalkoder\Routee\Consoles;

use Lokalkoder\Routee\RouteeSource;
use Tightenco\Ziggy\CommandRouteGenerator;

class RouteZiggyConsole extends CommandRouteGenerator
{
    protected $signature = 'routee:ziggy {path=./resources/js/global.js} {--url=/} {--group=}';

    protected $description = 'Generate ziggy js file for including in build process';

    /**
     * Generate SharedRoute js
     * Overwrite parent::generate()
     *
     * @param bool $group
     *
     * @return void
     */
    public function generate($group = false)
    {
        $this->prepareDomain();

        $json = (new RouteeSource())->routeCollection()->toJson();

        $defaultParameters = method_exists(app('url'), 'getDefaultParameters') ? json_encode(app('url')->getDefaultParameters()) : '[]';

        return <<<EOT
            var SharedRoute = {
                namedRoutes: $json,
                baseUrl: '{$this->baseUrl}',
                baseProtocol: '{$this->baseProtocol}',
                baseDomain: '{$this->baseDomain}',
                basePort: {$this->basePort},
                defaultParameters: $defaultParameters
            };

            if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
                for (var name in window.Ziggy.namedRoutes) {
                    SharedRoute.namedRoutes[name] = window.Ziggy.namedRoutes[name];
                }
            }

            export {
                SharedRoute
            }

        EOT;
    }

    /**
     * Overwrite parent::prepareDomain()
     *
     * @return void
     */
    private function prepareDomain()
    {
        $url = '';
        $parsedUrl = parse_url($url);

        $this->baseUrl = '';
        $this->baseProtocol = array_key_exists('scheme', $parsedUrl) ? $parsedUrl['scheme'] : 'http';
        $this->baseDomain = array_key_exists('host', $parsedUrl) ? $parsedUrl['host'] : '';
        $this->basePort = array_key_exists('port', $parsedUrl) ? $parsedUrl['port'] : 'false';
    }
}
