<?php

namespace AppBundle\Model;

class Apk
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $comment;

    /**
     * @param string $path
     * @param string $content
     */
    public function __construct(string $path, string $content, string $comment = null)
    {
        $this->path = $path;
        $this->content = $content;
        $this->comment = $comment;
    }

    /**
     * Gets the value of path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Gets the value of content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Gets the value of comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}
