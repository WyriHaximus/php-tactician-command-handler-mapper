<?php declare(strict_types=1);

namespace WyriHaximus\Tactician\CommandHandler;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

final class Mapper
{
    public static function mapInstantiated(string $path, string $namespace): iterable
    {
        foreach (self::map($path, $namespace) as $command => $handler) {
            yield $command => new $handler();
        }
    }

    public static function map(string $path, string $namespace, ?Reader $reader = null): iterable
    {
        $reader = $reader ?? new AnnotationReader();

        $directory = new RecursiveDirectoryIterator($path);
        $directory = new RecursiveIteratorIterator($directory);

        foreach ($directory as $node) {
            if (!is_file($node->getPathname())) {
                continue;
            }

            $file = substr($node->getPathname(), strlen($path));
            $file = ltrim($file, DIRECTORY_SEPARATOR);
            $file = rtrim($file, '.php');

            $class = $namespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $file);

            if (!class_exists($class)) {
                continue;
            }

            $handler = self::getHandlerByCommand($class, $reader);

            if (!class_exists($handler)) {
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
