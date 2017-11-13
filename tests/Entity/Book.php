<?php

namespace Paymaxi\Component\Query\Tests\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class Book
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $published = true;

    /**
     * @var Author[]
     */
    private $authors;

    public function __construct(string $name, \DateTime $birth)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->authors = new ArrayCollection();
    }

    /**
     * @return Author[]
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    public function addAuthor(Author $author)
    {
        $this->authors->add($author);
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @param bool $published
     */
    public function setPublished(bool $published)
    {
        $this->published = $published;
    }
}