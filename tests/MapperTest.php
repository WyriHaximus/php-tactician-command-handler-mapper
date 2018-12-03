<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Tactician\CommandHandler;

use ApiClients\Tools\TestUtilities\TestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use Prophecy\Argument;
use Test\App\Commands\AwesomesauceCommand;
use Test\App\Handlers\AwesomesauceHandler;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;
use WyriHaximus\Tactician\CommandHandler\Mapper;

/**
 * @internal
 */
final class MapperTest extends TestCase
{
    public function testMapInstantiated(): void
    {
        $path = \dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR;
        $map = \iterator_to_array(Mapper::mapInstantiated($path));

        self::assertSame(1, \count($map));
        self::assertTrue(isset($map[AwesomesauceCommand::class]));
        self::assertInstanceOf(AwesomesauceHandler::class, $map[AwesomesauceCommand::class]);
    }

    public function testMap(): void
    {
        $path = \dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR;
        $map = \iterator_to_array(Mapper::map($path));

        self::assertSame(
            [
                AwesomesauceCommand::class => AwesomesauceHandler::class,
            ],
            $map
        );
    }

    public function testGetHandlerByCommand(): void
    {
        $handler = new Handler([
            'handdler',
        ]);
        $reader = $this->prophesize(AnnotationReader::class);
        $reader->getClassAnnotation(Argument::type(\ReflectionClass::class), Handler::class)->willReturn($handler);
        $result = Mapper::getHandlerByCommand('stdClass', $reader->reveal());
        self::assertSame('handdler', $result);
    }

    public function testGetHandlerByCommandStdClass(): void
    {
        $result = Mapper::getHandlerByCommand('stdClass', new AnnotationReader());
        self::assertSame(null, $result);
    }
}
