<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    public function __construct(private readonly UserService $userService)
    {}


    #[Route('/users', name: 'user_list')]
    public function list(): Response
    {

        return $this->render('user/list.html.twig', [
            'users' => $this->userService->getAllUsers(),
        ]);
    }

    #[Route('/users/create', name: 'user_create')]
    public function create(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $savedUser = $this->userService->register($user, $user->getPassword());

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function edit(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $savedUser = $this->userService->register($user, $user->getPassword());

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

}