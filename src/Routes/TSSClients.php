<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\FiskalyAPI\API;


class TSSClients implements IRoute
{
    public static function register()
    {
        Route::add('/fiskaly/tss_clients', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                API::setLive(true);
                TualoApplication::result('clients', API::clients());
                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);

        Route::add('/fiskaly/tss_client_tx/(?P<client_id>[\w\-\_\|]+)', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                $db = TualoApplication::get('session')->getDB();

                API::setLive(true);

                $transactions = API::clientTransactions($matches['client_id']);
                $sql = 'update kassenterminals_client_id set last_tx_read = now() where tss_client_id = {client_id}';
                $db->direct($sql, [
                    'client_id' => $matches['client_id']
                ]);

                TualoApplication::result('clients', $transactions);
                foreach ($transactions as $transaction) {
                    if (isset($transaction['_id'])) {
                        // $transaction['tss'] = API::tss($transaction['tss_id']);
                        $sql = 'insert ignore into kassenterminals_client_id_tx 
                        (tss, id, tx_id, val) 
                        values 
                        ({tss}, {client_id}, {_id}, {val}) ';
                        $db->direct($sql, [
                            'client_id' => $transaction['client_id'],
                            'tx_id' => $transaction['_id'],
                            'tss_id' => $transaction['tss_id'],
                            'val' => json_encode($transaction)
                        ]);
                        // on conflict (client_id, tx_id) do update set tss_id = :tss_id, tx = :tx';
                    }
                }
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
                TualoApplication::result('success', true);
            }
        }, ['get', 'post'], true);
    }
}
