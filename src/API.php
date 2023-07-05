<?php
namespace Tualo\Office\Fiskaly;
use Tualo\Office\Basic\TualoApplication;
 
class API {

    private static mixed $ENV=null;

    public static function post(string $url, mixed $data) : mixed {
        $url = 'https://dsfinvk.fiskaly.com/api/v1'.'/'.$url;
        $ch = curl_init($url);
        $payload = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result,true);
    }

    public static function getEnvironment():array{
        if (is_null(self::$ENV)){
            $db = TualoApplication::get('session')->getDB();
            try{
                if (!is_null($db)){
                     $data = $db->directHash('select id,val from fiskaly_environments');
                     foreach($data as $d){
                         self::$ENV[$d['id']] = json_decode($d['val'],true);
                     }
                }
            }catch(\Exception $e){
            }
        }
        return self::$ENV;
    }

    public static function env($key){
        $env = self::getEnvironment();
        if (isset($env[$key])){
            return $env[$key];
        }
        throw new \Exception('Environment '.$key.' not found!');
    }

    public static function auth(){
        $result = self::post('auth',array(
            'api_key' => self::env('api_key'),
            'api_secret' => self::env('api_secret')
        ));
        print_r($result);
    }


}
