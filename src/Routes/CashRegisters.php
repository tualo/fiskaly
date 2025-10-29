<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\FiskalyAPI\API;


class CashRegisters extends \Tualo\Office\Basic\RouteWrapper
{
    public static function register()
    {
        Route::add('/fiskaly/cashregisters', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                $list = API::getCashRegisters();
                TualoApplication::result('cashregisters', $list);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get'], true);
    }
}
