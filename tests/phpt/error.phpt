--TEST--
Test catching errors
--FILE--
<?php
use Pinebucket\Client\Tests\Fixture\DummyPipebucket;

require_once dirname(__DIR__, 2).'/vendor/autoload.php';

DummyPipebucket::register('xyz');
trigger_error('Foo', E_USER_ERROR);
--EXPECT--
{"items":[{"message":"Foo","file":"Standard input code","line":7,"php_error_level":256}]}
