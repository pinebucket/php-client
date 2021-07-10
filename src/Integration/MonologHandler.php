<?php

declare(strict_types=1);

namespace Pinebucket\Client\Integration;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\ScalarFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Pinebucket\Client\Pinebucket;

/**
 * Monolog handler for Pinebucket.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MonologHandler extends AbstractProcessingHandler
{
    private $pinebucket;

    public function __construct(Pinebucket $pinebucket, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->pinebucket = $pinebucket;
    }

    protected function write(array $record): void
    {
        if (is_array($record['formatted'])) {
            $entry = $record['formatted'];
        } else {
            $entry = ['message' => $record['formatted']];
        }

        $this->pinebucket->sendSingle($entry);
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new ScalarFormatter();
    }
}
