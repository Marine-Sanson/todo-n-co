<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{


    /**
     * Summary of __construct
     *
     * @param UserService $userService UserService
     */
    public function __construct(private readonly UserService $userService)
    {

    }


    /**
     * Summary of list
     *
     * @return Response
     */
    #[Route('/users', name: 'user_list')]
    public function list(): Response
    {

        return $this->render(
            'user/list.html.twig', [
                'users' => $this->userService->getAllUsers(),
            ]
        );

    }


    /**
     * Summary of create
     *
     * @param Request $request Request
     *
     * @return Response|RedirectResponse
     */
    #[Route('/users/create', name: 'user_create')]
    public function create(Request $request): Response|RedirectResponse
    {

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() === true && $form->isValid() === true) {
            $role = $this->userService->dealRole($form->get("roles")->getData());
            $user->setRoles([$role]);

            $this->userService->register($user, $form->get('password')->getData());

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);

    }


    /**
     * Summary of edit
     *
     * @param User    $user    User
     * @param Request $request Request
     *
     * @return Response|RedirectResponse
     */
    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function edit(User $user, Request $request): Response|RedirectResponse
    {

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() === true && $userForm->isValid() === true) {
            $role = $this->userService->dealRole($userForm->get("roles")->getData());
            $user->setRoles([$role]);

            $this->userService->editUser($user);

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['userForm' => $userForm->createView(), 'user' => $user]);

    }


}
