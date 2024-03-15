<?php

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{


    public function testIndex(): void
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');
    
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Bienvenue');
    
    }


}
