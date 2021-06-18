<?php


namespace App\Tests\Fake;


use App\Entity\Contact;
use App\Manager\ContactManagerInterface;

class ContactManagerFake implements ContactManagerInterface
{

    public function getAll()
    {
        return [
            (new Contact())->setId(1)->setFirstName('A')->setLastName('B'),
            (new Contact())->setId(2)->setFirstName('C')->setLastName('D'),
        ];
    }
}