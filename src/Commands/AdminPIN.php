<?php

namespace Tualo\Office\Fiskaly\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use phpseclib3\Math\BigInteger\Engines\PHP;
use Tualo\Office\Basic\ICommandline;
use Tualo\Office\ExtJSCompiler\Helper;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\PostCheck;

use Tualo\Office\Fiskaly\Routes\Auth as T;
use Tualo\Office\FiskalyAPI\API;

class AdminPIN implements ICommandline
{

    public static function getCommandName(): string
    {
        return 'fiskaly-admin-pin';
    }

    public static function setup(Cli $cli)
    {
        $cli->command(self::getCommandName())
            ->description('admin pin management')
            ->opt('client', 'only use this client', true, 'string')
            ->opt('tss', 'tss identifier', true, 'string')
            ->opt('pin', 'admin pin', true, 'string')
            ->opt('puk', 'admin puk', true, 'string')
            ->opt('live', 'setup live tse', false, 'boolean');
    }


    public static function setupClients(string $msg, string $clientName, Args $args)
    {
        $_SERVER['REQUEST_URI'] = '';
        $_SERVER['REQUEST_METHOD'] = 'none';
        App::run();

        $session = App::get('session');
        $sessiondb = $session->db;
        $dbs = $sessiondb->direct('select username db_user, password db_pass, id db_name, host db_host, port db_port from macc_clients ');
        foreach ($dbs as $db) {
            if (($clientName != '') && ($clientName != $db['db_name'])) {
                continue;
            } else {
                PostCheck::formatPrint(['blue'], $msg . '(' . $db['db_name'] . '):  ');
                try {
                    $db = $session->newDBByRow($db);
                    API::db($db);
                    $data = $db->direct('insert into fiskaly_tss (tss,id,val) values ({guid},{id},{val}) on duplicate key update val={val}', [
                        'guid' => $args->getOpt('tss'),
                        'id' => 'admin_pin',
                        'val' => $args->getOpt('pin')
                    ]);
                    $data = $db->direct('insert into fiskaly_tss (tss,id,val) values ({guid},{id},{val}) on duplicate key update val={val}', [
                        'guid' => $args->getOpt('tss'),
                        'id' => 'admin_puk',
                        'val' => $args->getOpt('puk')
                    ]);
                    API::auth();
                    // API::personalizeTSS();
                    API::adminPin();
                } catch (\Exception $e) {
                    PostCheck::formatPrintLn(['red'], "\t" . ' ERROR: ' . $e->getMessage());
                    continue;
                }
                PostCheck::formatPrintLn(['green'], "\t" . ' done');
            }
        }
    }

    public static function run(Args $args)
    {
        $clientName = $args->getOpt('client');
        if (is_null($clientName)) exit();
        self::setupClients('create a tse on fiskaly', $clientName, $args);
    }
}
