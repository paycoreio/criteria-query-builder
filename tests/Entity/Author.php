<?php

namespace Paymaxi\Component\Query\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;


class Author
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
     * @var \DateTime
     */
    private $birth;

    /**
     * @var Book[]
     */
    private $books;

    public function __construct(string $name, \DateTime $birth)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->birth = $birth;
        $this->books = new ArrayCollection();
    }

    public function addBook(Book $book)
    {
        $book->setAuthor($this);

        $this->books->add($book);
    }

    /**
     * @return Book[]
     */
    public function getBooks(): array
    {
        return $this->books;
    }

    /**
     * @param Book[] $books
     */
    public function setBooks(array $books)
    {
        $this->books = $books;
    }

}