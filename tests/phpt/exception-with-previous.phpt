--TEST--
Test logging exceptions with previous handler
--FILE--
<?php
use Pinebucket\Client\Tests\Fixture\DummyPipebucket;

require_once dirname(__DIR__, 2).'/vendor/autoload.php';

$handler = function(\Throwable $e) {
    echo 'Exception: '.get_class($e)."\n";
};
set_exception_handler($handler);

DummyPipebucket::register('xyz');

throw new RuntimeException('My exception');
--EXPECT--
{"items":[{"message":"My exception","file":"Standard input code","line":13,"exception_class":"RuntimeException","exception_trace":"#0 {main}","exception_code":0}]}
Exception: RuntimeException
