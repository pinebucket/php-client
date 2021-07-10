--TEST--
Test logging errors with previous handler
--FILE--
<?php
use Pinebucket\Client\Tests\Fixture\DummyPipebucket;

require_once dirname(__DIR__, 2).'/vendor/autoload.php';

$handler = function(int $level, string $message, string $file = null, int $line = null): bool {
    echo 'Error: '.$message."\n";

    return true;
};
set_error_handler($handler);

DummyPipebucket::register('xyz');
trigger_error('Foo', E_USER_ERROR);

--EXPECT--
{"items":[{"message":"Foo","file":"Standard input code","line":14,"php_error_level":256}]}
Error: Foo
