<?php

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
     * @param string $handler
     */
    public function __construct($handler)
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
