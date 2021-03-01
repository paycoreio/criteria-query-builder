<?php

namespace Paymaxi\Component\Query\Tests\Entity;


class BookWithCreated extends Book
{
    private $created;

    public function __construct(string $name, \DateTime $birth)
    {
        parent::__construct($name, $birth);
        $this->created  = new \DateTime('now');
    }
}