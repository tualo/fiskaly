<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\FiskalyAPI\API;


class CreateClient implements IRoute
{
    public static function register()
    {


        Route::add('/fiskaly/createClient/(?P<terminalid>[\w\-\_]+)', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                API::authenticateAdmin();
                TualoApplication::result('data', API::createClient($matches['terminalid']));
                API::logoutAdmin();
                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, array('get', 'post'), true, [
            'errorOnUnexpected' => false,
            'errorOnInvalid' => false,
            'fields' => [
                '_dc' => [
                    'required' => false,
                    'type' => 'int',
                ]
            ]
        ]);
    }
}
