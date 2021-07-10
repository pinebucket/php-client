# Pinebucket PHP Client

Pinebucket is a simple logging platform where you can easily search for log entries
across multiple platform. Is is not a big complex monster, but a good first tool
to get started with professional logging.

## Install

```
composer require pinebucket/php-client
```

## Use

The simplest way to get started is to register error handlers. This will make sure
we log all errors and uncaught exceptions.

```php
\Pinebucket\Client\Pinebucket::register('my-project-key');
```

### Monolog

You may also integrate with Monolog my using the `MonologHandler`.

### PSR-3

Or use the `PinebucketLogger` which is a PSR-3 logger.
