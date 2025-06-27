<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\FiskalyAPI\API;


class VatDefinitions implements IRoute
{
    public static function register()
    {
        Route::add('/fiskaly/vat_definitions', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                TualoApplication::result('data', API::getVatDefinitions());
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get'], true);
    }
}
