<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Tactician\CommandHandler\Annotations;

use ApiClients\Tools\TestUtilities\TestCase;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

final class HandlerTest extends TestCase
{
    public function testGetHandler()
    {
        $handler = new Handler([
            'handler',
        ]);
        self::assertSame('handler', $handler->getHandler());
    }
}
