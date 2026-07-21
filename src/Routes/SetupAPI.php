<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\FiskalyAPI\API;


class SetupAPI extends \Tualo\Office\Basic\RouteWrapper
{
    public static function setup(
        bool $isLive = false,
        string $apiName = 'tualo',
        string $apiKey = '',
        string $apiSecret = '',
        mixed $db = null
    ) {
        if (is_null($db)) {
            $db = TualoApplication::get('session')->getDB();
        }
        if (is_null($db)) {
            throw new \Exception('No database connection found. Please check your configuration.');
        }

        $db->direct('insert ignore into fiskaly_environments (id,val,type) values ({id},{val},{type})', [
            'id' => 'api_secret',
            'val' => $apiSecret,
            'type' => $isLive ? 'live' : 'test'
        ]);

        $db->direct('insert ignore into fiskaly_environments (id,val,type) values ({id},{val},{type})', [
            'id' => 'api_key',
            'val' => $apiKey,
            'type' => $isLive ? 'live' : 'test'
        ]);


        $db->direct('insert ignore into fiskaly_environments (id,val,type) values ({id},{val},{type})', [
            'id' => 'api_name',
            'val' => $apiName,
            'type' => $isLive ? 'live' : 'test'
        ]);

        $db->direct("insert ignore into fiskaly_environments (id,type,val) values 
        ('dsfinvk_base_url','test','https://dsfinvk.fiskaly.com/api/v1'),
        ('sign_base_url','test','https://kassensichv-middleware.fiskaly.com/api/v2'),
        ('dsfinvk_base_url','live','https://dsfinvk.fiskaly.com/api/v1'),
        ('sign_base_url','live','https://kassensichv-middleware.fiskaly.com/api/v2');");
    }

    public static function register()
    {
        Route::add('/fiskaly/setup-api', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('create', self::setup(
                    TualoApplication::get('request')->get('live', false),
                    TualoApplication::get('request')->get('name', 'tualo'),
                    TualoApplication::get('request')->get('key', ''),
                    TualoApplication::get('request')->get('secret', '')
                ));
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['post'], true);
    }
}
