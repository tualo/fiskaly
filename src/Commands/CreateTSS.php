<?php

namespace Tualo\Office\Fiskaly\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use phpseclib3\Math\BigInteger\Engines\PHP;
use Tualo\Office\Basic\ICommandline;
use Tualo\Office\ExtJSCompiler\Helper;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\PostCheck;

use Tualo\Office\Fiskaly\Routes\CreateTSS as T;
use Tualo\Office\FiskalyAPI\API;

class CreateTSS implements ICommandline
{

    public static function getCommandName(): string
    {
        return 'create-tss';
    }

    public static function setup(Cli $cli)
    {
        $cli->command(self::getCommandName())
            ->description('create an tse')
            ->opt('client', 'only use this client', true, 'string')
            ->opt('live', 'setup live tse', false, 'boolean');
    }


    public static function setupClients(string $msg, string $clientName, bool $live = false)
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
                    T::create($live, $session->newDBByRow($db));
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
        self::setupClients('create a tse on fiskaly', $clientName, $args->getOpt('live', false));
    }
}
