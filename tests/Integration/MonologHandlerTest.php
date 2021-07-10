<?php

declare(strict_types=1);

namespace Pinebucket\Client\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Pinebucket\Client\Integration\MonologHandler;
use Pinebucket\Client\Pinebucket;

class MonologHandlerTest extends TestCase
{
    public function testAlert()
    {
        $pinebucket = $this->getMockBuilder(Pinebucket::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['sendSingle'])
            ->getMock();

        $pinebucket->expects($this->once())
            ->method('sendSingle')
            ->with(['level' => '200', 'message' => 'Foobar', 'extra' => '[]', 'context' => '[]'])
            ->willReturn(true);

        $logger = new MonologHandler($pinebucket);
        $logger->handle(['level' => '200', 'message' => 'Foobar', 'extra' => [], 'context' => []]);
    }
}
