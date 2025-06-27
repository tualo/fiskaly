<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\FiskalyAPI\API;


class CreateTSS implements IRoute
{
    public static function create($isLive = false, $db = null)
    {
        if (is_null($db)) {
            $db = TualoApplication::get('session')->getDB();
        }
        if (is_null($db)) {
            throw new \Exception('No database connection found. Please check your configuration.');
        }

        API::db($db);
        API::setLive($isLive);
        $env = API::getEnvironment();


        if (isset($env['guid'])) {
            throw new \Exception(' TSS/TSE allready exists.');
        }

        $auth = API::auth([
            'Content-Type:application/json'
        ]);
        if (is_null($auth['access_token']) || empty($auth['access_token'])) {
            throw new \Exception('No access token found. Please authenticate first.');
        }
        $tss = API::createTSS([
            'Authorization: Bearer ' . $auth['access_token'],
            'Content-Type:application/json'
        ]);
        return $tss;
    }

    public static function register()
    {
        Route::add('/fiskaly/createTSS', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('create', self::create(false));
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
