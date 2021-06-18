<?php

namespace App\Tests\Controller;

use App\Entity\Contact;
use App\Manager\ContactManagerInterface;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    use ProphecyTrait;

    public function testList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contacts/');

        $this->assertResponseIsSuccessful();
        $this->assertCount(3, $crawler->filter('tr'), 'Should contain 2 contact lines + header');
    }

    public function testListWithProphecy(): void
    {
        $client = static::createClient();

        $mock = $this->prophesize(ContactManagerInterface::class);
        $mock->getAll()->willReturn([
            (new Contact())->setId(1)->setFirstName('A')->setLastName('B'),
            (new Contact())->setId(2)->setFirstName('C')->setLastName('D'),
            (new Contact())->setId(3)->setFirstName('E')->setLastName('F'),
            (new Contact())->setId(4)->setFirstName('G')->setLastName('H'),
        ]);

        static::getContainer()->set(ContactManagerInterface::class, $mock->reveal());

        $crawler = $client->request('GET', '/contacts/');

        $this->assertResponseIsSuccessful();
        $this->assertCount(5, $crawler->filter('tr'), 'Should contain 4 contact lines + header');
    }
}
