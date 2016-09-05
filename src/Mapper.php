<?php

namespace WyriHaximus\Tactician\CommandHandler;

use Doctrine\Common\Annotations\AnnotationReader;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

final class Mapper
{
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

            $annotation = $reader->getClassAnnotation(new ReflectionClass($class), Handler::class);

            if (!($annotation instanceof Handler)) {
                continue;
            }

            $mapping[$class] = $annotation->getHandler();
        }

        return $mapping;
    }

    public static function mapInstantiated($path, $namespace)
    {
        $mapping = [];

        foreach (self::map($path, $namespace) as $command => $handler) {
            $mapping[$command] = new $handler();
        }

        return $mapping;
    }
}
