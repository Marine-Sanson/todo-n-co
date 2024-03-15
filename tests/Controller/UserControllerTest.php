<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Controller\UserController;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testList(): void
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByUsername('admin');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');

    }

    // public function testCreate(): void
    // {
        // Given
        // $client = static::createClient();
        // $userRepository = static::getContainer()->get(UserRepository::class);
        // $testUser = $userRepository->findOneByEmail('user@ex.com');
        // $client->loginUser($testUser);

        // // When

        // // Then
        // $this->assertInstanceOf(User::class, $testUser);

    // Peut on tester le save ?
    // }

    // public function edit()
    // {
    //     $form = $this->createForm(UserType::class, $user);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->userService->register($user, $user->getPassword());

    //         $this->addFlash('success', "L'utilisateur a bien été modifié");

    //         return $this->redirectToRoute('user_list');
    //     }

    //     return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    // }

}
