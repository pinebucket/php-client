--TEST--
Test logging exceptions
--FILE--
<?php
use Pinebucket\Client\Tests\Fixture\DummyPipebucket;

require_once dirname(__DIR__, 2).'/vendor/autoload.php';

DummyPipebucket::register('xyz');

throw new RuntimeException('My exception');
--EXPECT--
{"items":[{"message":"My exception","file":"Standard input code","line":8,"exception_trace":"#0 {main}","exception_code":0}]}
