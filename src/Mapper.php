<?php declare(strict_types=1);

namespace WyriHaximus\Tactician\CommandHandler;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;
use function WyriHaximus\listClassesInDirectory;

final class Mapper
{
    public static function mapInstantiated(string $path, ?Reader $reader = null): iterable
    {
        foreach (self::map($path, $reader) as $command => $handler) {
            yield $command => new $handler();
        }
    }

    public static function map(string $path, ?Reader $reader = null): iterable
    {
        foreach (listClassesInDirectory($path) as $class) {
            $handler = self::getHandlerByCommand($class, $reader);

            if ($handler !== null && !class_exists($handler)) {
                continue;
            }

            yield $class => $handler;
        }
    }

    public static function getHandlerByCommand(string $command, ?Reader $reader = null): ?string
    {
        $annotation = ($reader ?? new AnnotationReader())->getClassAnnotation(new ReflectionClass($command), Handler::class);

        if (!($annotation instanceof Handler)) {
            return null;
        }

        return $annotation->getHandler();
    }
}
