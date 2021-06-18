<?php


namespace App\Manager;


use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;

class ContactManager implements ContactManagerInterface
{
    /** @var ManagerRegistry */
    protected $doctrine;

    /**
     * ContactManager constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getAll()
    {
        return $this->doctrine->getRepository(Contact::class)->findAll();
    }
}