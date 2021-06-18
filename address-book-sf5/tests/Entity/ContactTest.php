<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    /** @var Contact */
    protected $entity;

    protected function setUp(): void
    {
        $this->entity = new Contact();
    }

    public function testFirstName(): void
    {
        $this->entity->setFirstName('Romain');
        $this->assertEquals('Romain', $this->entity->getFirstName());
    }
}
