<?php

namespace WyriHaximus\Tests\Tactician\CommandHandler;

use Doctrine\Common\Annotations\AnnotationReader;
use Phake;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;
use WyriHaximus\Tactician\CommandHandler\Mapper;

class MapperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHandlerByCommand()
    {
        $handler = new Handler([
            'handdler',
        ]);
        $reader = Phake::mock(AnnotationReader::class);
        Phake::when($reader)->getClassAnnotation($this->isInstanceOf('ReflectionClass'), Handler::class)->thenReturn($handler);
        $result = Mapper::getHandlerByCommand('stdClass', $reader);
        $this->assertSame('handdler', $result);
    }

    public function testGetHandlerByCommandStdClass()
    {
        $result = Mapper::getHandlerByCommand('stdClass', new AnnotationReader());
        $this->assertSame('', $result);
    }
}
