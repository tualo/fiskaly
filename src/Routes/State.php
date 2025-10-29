<?php

namespace Tualo\Office\Fiskaly\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\FiskalyAPI\API;


class State extends \Tualo\Office\Basic\RouteWrapper
{
    public static function register()
    {
        Route::add('/fiskaly/state', function ($matches) {
            TualoApplication::contenttype('application/json');
            try {
                $messages = [];



                $db = TualoApplication::get('session')->getDB();
                try {
                    if (!is_null($db)) {
                        $data = $db->direct('select id,val from fiskaly_environments where type={type}', [
                            'type' => 'test'
                        ]);
                        $data = $db->direct('select id,val from fiskaly_tss', [
                            'type' => 'test'
                        ]);
                    }
                } catch (\Exception $e) {
                    $messages[] = [
                        'color' => 'red',
                        'icon' => 'warning',
                        'text' => 'Tables <b>fiskaly_environments</b> and <b>fiskaly_tss</b> not found in database. Please run `./tm install-fiskaly` to create them.'
                    ];
                }


                $tests = [[
                    'key' => 'test',
                    'title' => 'Test-System',
                ], [
                    'key' => 'live',
                    'title' => '<strong>Live-System</strong>',
                ]];



                foreach ($tests as  $t) {
                    API::resetEnvrionment();

                    //API::setLive($t['key'] == 'live');
                    $env = API::getEnvironment();
                    API::auth();


                    if (!isset($env['api_key']) || (!isset($env['api_secret']))) {
                        $messages[] = [
                            'color' => 'red',
                            'icon' => 'warning',
                            'text' => $t['title'] . ': API Key or API Secret not set in environment variables. '
                        ];
                    } else {
                        $messages[] = [
                            'color' => 'green',
                            'icon' => 'check',
                            'text' => $t['title'] . ': API Key and API Secret found. '
                        ];
                    }

                    if (!isset($env['sign_base_url'])) {
                        $messages[] = [
                            'color' => 'red',
                            'icon' => 'warning',
                            'text' => $t['title'] . ': sign_base_url not set in environment variables. '
                        ];
                    } else {
                        $messages[] = [
                            'color' => 'green',
                            'icon' => 'check',
                            'text' => $t['title'] . ': API-Sign url found: ' .
                                '<b>' . $env['sign_base_url'] . '</b>   '
                        ];
                    }

                    if (!isset($env['dsfinvk_base_url'])) {
                        $messages[] = [
                            'color' => 'red',
                            'icon' => 'warning',
                            'text' => $t['title'] . ': dsfinvk_base_url not set in environment variables. '
                        ];
                    } else {
                        $messages[] = [
                            'color' => 'green',
                            'icon' => 'check',
                            'text' => $t['title'] . ': API-DS FinVK url found: ' .
                                '<b>' . $env['dsfinvk_base_url'] . '</b>   '
                        ];
                    }



                    if (!isset($env['guid'])) {
                        $messages[] = [
                            'color' => 'orange',
                            'icon' => 'warning',
                            'text' => $t['title'] . ': there is no TSE. ' .
                                'Please run <b>./tm create-tss --client &lt;system&gt; [--live]</b> to create a TSE. '
                        ];
                    } else {
                        $messages[] = [
                            'color' => 'green',
                            'icon' => 'check',
                            'text' => $t['title'] . ': TSE found. '
                        ];
                    }

                    $tss = API::getTSS();
                    if (isset($tss['state']) && ($tss['state'] == 'CREATED')) {
                        $messages[] = [
                            'color' => 'orange',
                            'icon' => 'warning',
                            'text' => $t['title'] . ': TSS is not ready for use *CREATED*. ' .
                                'Please run <b>./tm personalize-tss --pin  &lt;yourpin&gt; --client &lt;system&gt;  [--live]</b>. '
                        ];
                    } else if (isset($tss['state']) && ($tss['state'] == 'UNINITIALIZED')) {
                        $messages[] = [
                            'color' => 'orange',
                            'icon' => 'warning',

                            'text' => $t['title'] . ': TSS is not ready for use *UNINITIALIZED*. '
                                . 'Please run <b>./tm initialize-tss --client &lt;system&gt; [--live]</b> to initialize the TSS. '
                        ];
                    } else if (!isset($tss['state'])) {
                        $messages[] = [
                            'color' => 'red',
                            'icon' => 'warning',
                            'text' => $t['title'] . ': TSS is not ready for use. Please run <b>./tm create-tss --client &lt;system&gt; [--live]</b> to create a TSS. '
                        ];
                    } else if ($tss['state'] == 'INITIALIZED') {

                        $clients = [
                            'count' => 0
                        ];

                        $expired = false;
                        try {

                            if ($expired = API::isExpired()) {
                                $messages[] = [
                                    'color' => 'red',
                                    'icon' => 'warning',
                                    'text' => $t['title'] . ': TSS is expired. Please run <b>./tm renew-tss --client &lt;system&gt; [--live]</b> to renew the TSS.'
                                ];
                                // API::auth();
                            } else {
                                $messages[] = [
                                    'color' => 'green',
                                    'icon' => 'check',
                                    'text' => $t['title'] . ': TSS is not expired. '
                                ];
                            }
                        } catch (\Exception $e) {
                        }

                        try {
                            if (!$expired) {
                                $clients = API::clients();
                            }
                        } catch (\Exception $e) {
                            $messages[] = [
                                'color' => 'red',
                                'icon' => 'warning',
                                'text' => $e->getMessage()
                            ];
                        }
                        $messages[] = [
                            'color' => 'green',
                            'icon' => 'circle-check',
                            'text' => $t['title'] . ': TSS is initialized. ' .
                                ((isset($clients['count']) && ($clients['count'] == 0)) ? ' <font color="red">No registered clients found.</font> use your app to register clients ' :
                                    ' everything is fine ' . $clients['count'] . ' registered clients found.')

                        ];
                    } else {
                        $messages[] = [
                            'color' => 'green',
                            'icon' => 'check',
                            'text' => $t['title'] . ': TSS is ready for use. '
                        ];
                    }
                }
                TualoApplication::result('time', time());






                TualoApplication::result('test', $env);

                TualoApplication::result('messages', $messages);
                TualoApplication::result('success', true);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        },  ['get'], true);
    }
}
