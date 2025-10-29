<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\RouteSecurityHelper;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class JsLoader extends \Tualo\Office\Basic\RouteWrapper
{
    public static function register()
    {
        BasicRoute::add('/jsfiskaly/(?P<file>[\w.\/\-]+).js', function ($matches) {

            RouteSecurityHelper::serveSecureStaticFile(
                $matches['file'] . '.js',
                implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 1), 'js', 'lazy']),
                ['js'],
                [
                    'application/javascript',

                ]
            );
        }, ['get'], false);
    }
}
