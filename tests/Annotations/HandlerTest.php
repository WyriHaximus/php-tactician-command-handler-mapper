<?php

namespace WyriHaximus\Tests\Tactician\CommandHandler\Annotations;

use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHandler()
    {
        $handler = new Handler([
            'handler',
        ]);
        $this->assertSame('handler', $handler->getHandler());
    }
}
