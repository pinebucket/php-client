<?php

declare(strict_types=1);

namespace Pinebucket\Client;

/**
 * Make sure we can chain handlers.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class Handler
{
    /**
     * @var callable|null
     */
    private $nextExceptionHandler;

    /**
     * @var callable|null
     */
    private $nextErrorHandler;

    /**
     * @var Pinebucket
     */
    private $pinebucket;

    public function __construct(Pinebucket $pinebucket)
    {
        $this->pinebucket = $pinebucket;
    }

    public function handleException(\Throwable $exception)
    {
        $entry = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'exception_trace' => $exception->getTraceAsString(),
            'exception_code' => $exception->getCode(),
        ];

        $previous = $exception->getPrevious();
        if (null !== $previous) {
            $entry['previous_exception_message'] = $previous->getMessage();
            $entry['previous_exception_trace'] = $previous->getTraceAsString();
            $entry['previous_exception_code'] = $previous->getCode();
        }

        $this->pinebucket->sendSingle($entry);

        if ($handler = $this->nextExceptionHandler) {
            $handler($exception);
        }
    }

    public function handleError(int $level, string $message, string $file = null, int $line = null): bool
    {
        $this->pinebucket->sendSingle([
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'php_error_level' => $level,
        ]);

        if ($handler = $this->nextErrorHandler) {
            return $handler($level, $message, $file, $level);
        }

        return true;
    }

    public function handleShutdown()
    {
        $error = error_get_last();
        if (is_array($error)) {
            $this->pinebucket->sendSingle([
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line'],
                'php_error_level' => $error['type'],
            ]);
        }
    }

    public function setNextExceptionHandler(?callable $nextExceptionHandler): void
    {
        $this->nextExceptionHandler = $nextExceptionHandler;
    }

    public function setNextErrorHandler(?callable $nextErrorHandler): void
    {
        $this->nextErrorHandler = $nextErrorHandler;
    }
}
