<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testList(): void
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByUsername('admin');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des tâches');

    }
}
