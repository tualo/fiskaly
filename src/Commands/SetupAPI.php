<?php

namespace Tualo\Office\Fiskaly\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use phpseclib3\Math\BigInteger\Engines\PHP;
use Tualo\Office\Basic\ICommandline;
use Tualo\Office\ExtJSCompiler\Helper;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\PostCheck;

use Tualo\Office\Fiskaly\Routes\SetupAPI as T;
use Tualo\Office\FiskalyAPI\API;

class SetupAPI implements ICommandline
{

    public static function getCommandName(): string
    {
        return 'fiskaly-setup-api';
    }

    public static function setup(Cli $cli)
    {
        $cli->command(self::getCommandName())
            ->description('create an tse')
            ->opt('client', 'only use this client', true, 'string')
            ->opt('name', 'api name', true, 'string')
            ->opt('key', 'api key', true, 'string')
            ->opt('secret', 'api secret', true, 'string')
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
                    T::setup($args->getOpt('live', false), $args->getOpt('name', 'tualo'), $args->getOpt('key', ''), $args->getOpt('secret', ''), $session->newDBByRow($db));
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
        self::setupClients('setup-api on fiskaly', $clientName, $args);
    }
}
