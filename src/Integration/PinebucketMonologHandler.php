<?php

declare(strict_types=1);

namespace Pinebucket\Client\Integration;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Pinebucket\Client\Pinebucket;

/**
 * Monolog handler for Pinebucket.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class PinebucketMonologHandler extends AbstractProcessingHandler
{
    private $pinebucket;

    public function __construct(Pinebucket $pinebucket, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->pinebucket = $pinebucket;
    }

    protected function write(array $record): void
    {
        $this->pinebucket->sendSingle($record['formatted']);
    }
}
