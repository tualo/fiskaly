<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\FiskalyAPI\API;


class Auth implements IRoute
{
    public static function register()
    {
        Route::add('/fiskaly/auth', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                $auth = API::auth([
                    'Content-Type:application/json'
                ]);
                if (isset($auth['access_token'])) {
                    API::addEnvrionment('access_token', $auth['access_token']);
                    API::addEnvrionment('access_token_expires_at', $auth['access_token_expires_at']);
                }

                TualoApplication::result('time', time());
                TualoApplication::result('auth', $auth);
                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);

        Route::add('/fiskaly/cashregisters', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                $list = API::getCashRegisters();
                TualoApplication::result('cashregisters', $list);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);



        Route::add('/fiskaly/personalizeTSS', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::personalizeTSS());
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);

        Route::add('/fiskaly/initializeTSS', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::initializeTSS());
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);

        Route::add('/fiskaly/authenticateAdmin', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::authenticateAdmin());
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);

        Route::add('/fiskaly/adminPin', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::adminPin());
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);


        Route::add('/fiskaly/createClient/(?P<terminalid>[\w\-\_]+)', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::createClient($matches['terminalid']));
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);



        Route::add('/fiskaly/logoutAdmin', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::logoutAdmin());
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);


        Route::add('/fiskaly/transaction/(?P<type>[\w\-\_]+)/(?P<terminalid>[\w\-\_]+)', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::transaction(
                    $matches['terminalid'],
                    json_decode($_REQUEST['data'], true)
                    /*
                    [
                    [
                        'vat_rate' => '19',
                        'amount' => number_format(10.22,2,'.','')
                    ],
                    [
                        'vat_rate' => '7',
                        'amount' => number_format(3,2,'.','')
                    ]
                    ]*/,
                    $matches['type']
                ));
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);

        Route::add('/fiskaly/vat_definitions', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::getVatDefinitions());
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);

        Route::add('/pos/fiskaly/information/(?P<terminalid>[\w\-\_]+)', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::getTSSInformation($matches['terminalid']));
                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);


        Route::add('/fiskaly/test', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                error_reporting(E_ALL);
                ini_set('display_errors', '1');

                if (isset($_REQUEST['a'])   && ($_REQUEST['a'] == 'on')) {
                    TualoApplication::result('msg', 'ok');
                }
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);
    }
}
