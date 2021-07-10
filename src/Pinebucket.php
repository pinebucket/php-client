<?php

declare(strict_types=1);

namespace Pinebucket\Client;

class Pinebucket
{
    private const URL = 'https://input.pinebucket.com';
    protected const JSON_FLAGS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR;

    /**
     * @var string
     */
    private $projectSecret;

    /**
     * @var \CurlHandle|resource|false|null
     */
    private $curl;

    final public function __construct(string $projectSecret)
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
        $pinebucket = new static($projectSecret);
        $handler = new Handler($pinebucket);
        register_shutdown_function([$handler, 'handleShutdown']);
        $handler->setNextExceptionHandler(set_exception_handler([$handler, 'handleException']));
        // Specifying the error types earlier would expose us to https://bugs.php.net/63206
        $handler->setNextErrorHandler(set_error_handler([$handler, 'handleError'], 0x1FFF | 0));
    }

    /**
     * This will insert one data row.
     * Example input could be `['message'=>'An exception was thrown']`.
     */
    public function sendSingle(array $entry): bool
    {
        return $this->sendMultiple([$entry]);
    }

    /**
     * Insert one or more data rows.
     */
    public function sendMultiple(array $entries): bool
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
            CURLOPT_TIMEOUT => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode(['items' => $entries], self::JSON_FLAGS),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        curl_exec($this->curl);
        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        return 200 === (int) $statusCode;
    }
}
