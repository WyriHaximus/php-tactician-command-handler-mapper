# php-tractician-command-handler-mapper

Usage:

```php
$commandToHandlerMap = \Mapper::mapInstanciated(ROOT . DS . 'src' . DS . 'CommandBus', 'App\CommandBus');
$commandBus = QuickStart::create($commandToHandlerMap);
```
