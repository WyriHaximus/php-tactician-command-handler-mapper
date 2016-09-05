<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerLoader(function (string $class) {
    return class_exists($class);
});
