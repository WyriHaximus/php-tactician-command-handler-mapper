<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Tactician\CommandHandler\Annotations;

use ApiClients\Tools\TestUtilities\TestCase;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

/**
 * @internal
 */
final class HandlerTest extends TestCase
{
    public function testGetHandler(): void
    {
        $handler = new Handler([
            'handler',
        ]);
        self::assertSame('handler', $handler->getHandler());
    }
}
