<?php

declare(strict_types=1);

namespace Pinebucket\Client;

class Pinebucket
{
    private const URL = 'https://input.pinebucket.com';

    /**
     * @var string
     */
    private $projectSecret;

    /**
     * @var \CurlHandle|resource|false|null
     */
    private $curl;

    public function __construct(string $projectSecret)
    {
        $this->projectSecret = $projectSecret;
    }

    public function __destruct()
    {
        if ($this->curl) {
            curl_close($this->curl);
        }
    }

    public static function register(string $projectSecret)
    {
        $pinebucket = new self($projectSecret);
        $handler = new Handler($pinebucket);
        register_shutdown_function([$handler, 'handleShutdown']);
        $handler->setNextExceptionHandler(set_exception_handler([$handler, 'handleException']));
        // Specifying the error types earlier would expose us to https://bugs.php.net/63206
        $handler->setNextErrorHandler(set_error_handler([$handler, 'handleError'], 0x1FFF | 0));
    }

    public function sendSingle(array $entry)
    {
        $this->sendMultiple(['items' => [$entry]]);
    }

    public function sendMultiple(array $entries)
    {
        if (!$this->curl) {
            $this->curl = curl_init();
        }
        $headers = [
            'Content-Type: application/json',
            'X-Key: '.$this->projectSecret,
            'X-Format: 2',
            'Connection: keep-alive',
            'Keep-Alive: timeout=5, max=100',
        ];

        curl_setopt_array($this->curl, [
            CURLOPT_URL => self::URL,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FORBID_REUSE => false,
            CURLOPT_TIMEOUT => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($entries),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $server_output = curl_exec($this->curl);

        var_dump($server_output);
    }
}
