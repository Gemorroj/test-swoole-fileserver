<?php

namespace App;

use Swoole\Http\Client;
use Swoole\Http\Response;

class VideoDownloader
{
    public function getYoutube(string $id, Response $response): void
    {
        //todo: вынести в пулл конекшенов
        $client = new Client('www.youtube.com', 443, true);

        $client->setHeaders([
            'host' => 'www.youtube.com',
            'accept-encoding' => 'gzip, deflate',
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'accept-language' => 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4,ja;q=0.2',
            'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) Chrome/58.0.3026.3 Safari/537.36',
        ]);
        $client->setCookies(['a' => '1', 'b' => '2']);

        $client->get('/watch?v='.$id, function (Client $o) use ($client, $response) {
            $client->close(); // закрыватьтолько если нужно
            $response->end(print_r($o->body, 1)); //fixme: проблема - боди в памяти
        });
    }
}
