<?php

namespace WyriHaximus\Tactician\CommandHandler;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

final class Mapper
{
    /**
     * @param string $path
     * @param string $namespace
     * @return array
     */
    public static function mapInstantiated($path, $namespace)
    {
        $mapping = [];

        foreach (self::map($path, $namespace) as $command => $handler) {
            $mapping[$command] = new $handler();
        }

        return $mapping;
    }

    /**
     * @param string $path
     * @param string $namespace
     * @return array
     */
    public static function map($path, $namespace)
    {
        $reader = new AnnotationReader();
        $mapping = [];

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

            $mapping[$class] = $handler;
        }

        return $mapping;
    }

    /**
     * @param string $command
     * @param Reader $reader
     * @return string
     */
    public static function getHandlerByCommand($command, Reader $reader)
    {
        $annotation = $reader->getClassAnnotation(new ReflectionClass($command), Handler::class);

        if (!($annotation instanceof Handler)) {
            return '';
        }

        return $annotation->getHandler();
    }
}
