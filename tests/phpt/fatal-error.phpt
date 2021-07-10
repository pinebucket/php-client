--TEST--
Test catching fatal errors
--FILE--
<?php
use Pinebucket\Client\Tests\Fixture\DummyPipebucket;

require_once dirname(__DIR__, 2).'/vendor/autoload.php';

DummyPipebucket::register('xyz');

if (true) {
    class Broken implements \JsonSerializable
    {
    }
}

--EXPECT--
Fatal error: Class Broken contains 1 abstract method and must therefore be declared abstract or implement the remaining methods (JsonSerializable::jsonSerialize) in Standard input code on line 9
{"items":[{"message":"Class Broken contains 1 abstract method and must therefore be declared abstract or implement the remaining methods (JsonSerializable::jsonSerialize)","file":"Standard input code","line":9,"php_error_level":1}]}
