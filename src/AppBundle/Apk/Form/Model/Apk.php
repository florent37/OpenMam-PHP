<?php

namespace AppBundle\Apk\Form\Model;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class Apk
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Regex("/^[1-9](\d+)?(\.(0|([1-9](\d)?)+))\.(0|[1-9](\d+)?)$/")
     */
    private $version;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Regex("/^[1-9](\d+)?/")
     */
    private $code;

    /**
     * @var File
     * @Assert\File(
     *     maxSize="100M",
     *     mimeTypes={
     *         "application/zip"
     *     },
     *     mimeTypesMessage="The file is not an Apk valid."
     * )
     */
    private $file;

    /**
     * @var string
     */
    private $comment;

    /**
     * Gets the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the value of version.
     *
     * @param string $version the version
     *
     * @return self
     */
    public function setVersion(string $version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Gets the value of code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets the value of code.
     *
     * @param string $code the code
     *
     * @return self
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Gets the value of file.
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets the value of file.
     *
     * @param File $file the file
     *
     * @return self
     */
    public function setFile(File $file)
    {
        $this->file = $file;

        return $this;
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

    /**
     * Sets the value of comment.
     *
     * @param string $comment the comment
     *
     * @return self
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;

        return $this;
    }
}
