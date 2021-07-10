<?php

declare(strict_types=1);

namespace Pinebucket\Client\Tests\Fixture;

use Pinebucket\Client\Pinebucket;

class DummyPipebucket extends Pinebucket
{
    public function sendMultiple(array $entries): bool
    {
        echo json_encode(['items' => $entries], self::JSON_FLAGS);

        return true;
    }
}
