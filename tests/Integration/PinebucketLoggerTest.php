<?php

declare(strict_types=1);

namespace Pinebucket\Client\Tests;

use PHPUnit\Framework\TestCase;
use Pinebucket\Client\Integration\PinebucketLogger;
use Pinebucket\Client\Pinebucket;

class PinebucketLoggerTest extends TestCase
{
    public function testAlert()
    {
        $pinebucket = $this->getMockBuilder(Pinebucket::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['sendSingle'])
            ->getMock();

        $pinebucket->expects($this->once())
            ->method('sendSingle')
            ->with(['level' => 'alert', 'message' => 'Foobar', 'context_x' => 'y'])
            ->willReturn(true);

        $logger = new PinebucketLogger($pinebucket);
        $logger->alert('Foobar', ['x' => 'y']);
    }
}
