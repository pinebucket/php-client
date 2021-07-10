<?php

declare(strict_types=1);


namespace Pinebucket\Client;


/**
 * Make sure we can chain handlers.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @internal
 */
class Handler
{
    private $nextExceptionHandler;
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
            'exception' => $exception,
        ];

        $previous = $exception->getPrevious();
        if ($previous !== null) {
            $entry['previous_exception_message'] = $previous->getMessage();
            $entry['previous_exception'] = $previous;
        }

        $this->pinebucket->sendSingle($entry);
    }

    public function handleError(int $level, string $message, string $file = null, int $line = null): bool
    {
        $this->pinebucket->sendSingle([
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'php_error_level' => $level,
        ]);
    }

    public function handleShutdown()
    {
        $error = error_get_last();
        if (is_array($error)) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
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
