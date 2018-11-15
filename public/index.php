<?php

require __DIR__.'/../vendor/autoload.php';

use App\VideoDownloader;

$server = new \Swoole\Http\Server('0.0.0.0', 9501, \SWOOLE_PROCESS, \SWOOLE_SOCK_TCP | \SWOOLE_SSL);
$server->set([
    //'daemonize' => 1,
    'pid_file' => __DIR__.'/../var/server.pid',
    'log_file' => __DIR__.'/../var/server.log',
    /*
0 =>DEBUG
1 =>TRACE
2 =>INFO
3 =>NOTICE
4 =>WARNING
5 =>ERROR
     */
    'log_level' => 1,
    'open_http2_protocol' => true,
    'ssl_cert_file' => __DIR__.'/../ssl-cert-snakeoil.pem',
    'ssl_key_file' => __DIR__.'/../ssl-cert-snakeoil.key',
    'ssl_method' => SWOOLE_TLSv1_2_METHOD,

    /*
     * todo
    'max_conn' => 0,
    'max_request' => 0,
    'worker_num' => 0,
    'dispatch_mode' => 2,
    */
]);

$server->on('start', function (\Swoole\Http\Server $server) {
    \printf("Swoole http server is started at %s:%d\n", $server->host, $server->port);
});
$server->on('request', function (Swoole\Http\Request $request, Swoole\Http\Response $response) {
    $response->gzip(6);
    $response->header('Content-Type', 'text/plain; charset=utf-8');

    /*$response->end(print_r([
        'request header' => $request->header,
        'request server' => $request->server,
    ], 1));*/

    $paths = \explode('/', $request->server['request_uri'], 3);
    if (3 !== \count($paths)) {
        $response->end();
        return;
    }

    $videoDownloader = new VideoDownloader();
    $videoDownloader->getYoutube('HhJs1O3YV8c', $response);
});

$server->start();
