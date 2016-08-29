<?php

use Doctrine\Common\Annotations\AnnotationReader;

class Mapper
{
    public static function map($path, $namespace)
    {
        $mapping = [];

        $directory = new RecursiveDirectoryIterator($path);
        $directory = new RecursiveIteratorIterator($directory);

        foreach ($directory as $node) {
            if (!is_file($node->getPathname())) {
                continue;
            }

            $pair = self::checkFile($node->getPathname());
        }

        return $mapping;
    }

    protected static function checkFile($file)
    {
        echo $file, PHP_EOL;
        $class = self::getClassFromFile($file);
        echo $class, PHP_EOL;
        $reader = new AnnotationReader();
        $annotation = $reader->getClassAnnotation(new ReflectionClass($class), Handler::class);
        debug($annotation);die();
    }

    protected static function getClassFromFile($file)
    {
        $fp = fopen($file, 'r');
        $class = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) break;

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (strpos($buffer, '{') === false) continue;

            for (;$i<count($tokens);$i++) {
                if ($tokens[$i][0] === T_CLASS) {
                    for ($j=$i+1;$j<count($tokens);$j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i+2][1];
                        }
                    }
                }
            }
        }

        fclose($fp);

        return $class;
    }
}
