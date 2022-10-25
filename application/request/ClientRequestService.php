<?php declare(strict_types=1);

namespace app\application\request;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\StreamInterface;
use yii\web\BadRequestHttpException;

class ClientRequestService
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getBodyPage(string $url, bool $isAjax = false): StreamInterface
    {
        $headers = [
            'User-Agent' => $this->getUserAgent(),
        ];

        if ($isAjax) {
            $headers['X-Requested-With'] = 'XMLHttpRequest';
        }

        $response = $this->client->request('GET', $url, [
            'connect_timeout' => 10,
            'headers' => $headers,
        ]);

        if ($response->getStatusCode() > 400) {
            throw new BadRequestHttpException();
        }

        return $response->getBody();
    }

    private function getUserAgent(): string
    {
        $arr = [
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 12; SM-S906N Build/QP1A.190711.020; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/80.0.3987.119 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 10; SM-G996U Build/QP1A.190711.020; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 10; SM-G980F Build/QP1A.190711.020; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/78.0.3904.96 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 10; Google Pixel 4 Build/QD1A.190821.014.C2; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/78.0.3904.108 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 6P Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Mobile Safari/537.36'
        ];

        return $arr[array_rand($arr)];
    }
}
