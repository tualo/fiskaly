<?php
namespace Tualo\Office\Fiskaly\Routes;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Fiskaly\API;


class Auth implements IRoute{
    public static function register(){
        Route::add('/fiskaly/auth',function($matches){
            App::contenttype('application/json');
            try{
                $auth = API::auth([
                    'Content-Type:application/json'
                ]);
                if (isset($auth['access_token'])){
                    API::addEnvrionment('access_token',$auth['access_token']);
                    API::addEnvrionment('access_token_expires_at',$auth['access_token_expires_at']);
                }

                App::result('time',time() );
                App::result('auth',$auth );
                App::result('success',true );
                // App::result('cashregisters',$list );

            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);

        Route::add('/fiskaly/cashregisters',function($matches){
            App::contenttype('application/json');
            try{
                $list = API::getCashRegisters();
                App::result('cashregisters',$list );
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);

        Route::add('/fiskaly/createTSS',function($matches){
            App::contenttype('application/json');
            try{
                App::result('create', API::createTSS([
                    'Authorization: Bearer {{access_token}}',
                    'Content-Type:application/json'
                ]));
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);

        Route::add('/fiskaly/personalizeTSS',function($matches){
            App::contenttype('application/json');
            try{
                App::result('data', API::personalizeTSS( ));
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);

        Route::add('/fiskaly/initializeTSS',function($matches){
            App::contenttype('application/json');
            try{
                App::result('data', API::initializeTSS( ));
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);

        Route::add('/fiskaly/authenticateAdmin',function($matches){
            App::contenttype('application/json');
            try{
                App::result('data', API::authenticateAdmin( ));
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);

        Route::add('/fiskaly/adminPin',function($matches){
            App::contenttype('application/json');
            try{
                App::result('data', API::adminPin( ));
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);


        Route::add('/fiskaly/createClient',function($matches){
            App::contenttype('application/json');
            try{
                App::result('data', API::createClient( ));
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);


        
        Route::add('/fiskaly/logoutAdmin',function($matches){
            App::contenttype('application/json');
            try{
                App::result('data', API::logoutAdmin( ));
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);


        Route::add('/fiskaly/transaction',function($matches){
            App::contenttype('application/json');
            try{
                App::result('data', API::transaction([
                    [
                        'vat_rate' => '19',
                        'amount' => number_format(10.22,2,'.','')
                    ],
                    [
                        'vat_rate' => '7',
                        'amount' => number_format(3,2,'.','')
                    ]
                    ]/*,'RECEIPT'*/));
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);

        Route::add('/fiskaly/vat_definitions',function($matches){
            App::contenttype('application/json');
            try{
                App::result('data', API::getVatDefinitions());
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);

    }
}