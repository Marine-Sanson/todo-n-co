<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
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
