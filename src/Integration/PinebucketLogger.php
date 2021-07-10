<?php

declare(strict_types=1);

namespace Pinebucket\Client\Integration;

use Pinebucket\Client\Pinebucket;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * PSR-3 logger.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class PinebucketLogger implements LoggerInterface
{
    use LoggerTrait;

    private $pinebucket;

    public function __construct(Pinebucket $pinebucket)
    {
        $this->pinebucket = $pinebucket;
    }

    public function log($level, $message, array $context = [])
    {
        $entry = [
            'level' => $level,
            'message' => $message,
        ];

        foreach ($context as $name => $value) {
            $entry['context_'.$name] = $value;
        }

        $this->pinebucket->sendSingle($entry);
    }
}
