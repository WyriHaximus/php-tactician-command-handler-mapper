<?php

namespace WyriHaximus\Tactician\CommandHandler\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Handler
{
    /**
     * @var string
     */
    private $handler;

    /**
     * Handler constructor.
     * @param array $handler
     */
    public function __construct(array $handler)
    {
        $this->handler = current($handler);
    }

    /**
     * @return string
     */
    public function getHandler()
    {
        return $this->handler;
    }
}
