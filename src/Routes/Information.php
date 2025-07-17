<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\FiskalyAPI\API;
use Tualo\Office\FiskalyAPI\ApiException;


class Information implements IRoute
{
    public static function register()
    {


        Route::add('/fiskaly/information/(?P<terminalid>[\w\-\_]+)', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                // API::setLive(true);
                TualoApplication::result('data', API::getTSSInformation($matches['terminalid']));
                TualoApplication::result('success', true);
            } catch (ApiException $e) {
                http_response_code($e->getResponseCode());
                TualoApplication::result('msg', $e->getMessage());
            } catch (\Exception $e) {
                http_response_code(500);
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true, [
            'errorOnUnexpected' => false,
            'errorOnInvalid' => false,
            'fields' => [
                '_dc' => [
                    'required' => false,
                    'type' => 'int',
                ]
            ]
        ]);

        /*
        Route::add('/pos/fiskaly/information/(?P<terminalid>[\w\-\_]+)', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::getTSSInformation($matches['terminalid']));
                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true);
        */
    }
}
