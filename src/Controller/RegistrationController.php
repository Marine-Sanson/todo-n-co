<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
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
     * Summary of register
     *
     * @param Request                     $request            Request
     * @param UserPasswordHasherInterface $userPasswordHasher UserPasswordHasherInterface
     * @param EntityManagerInterface      $entityManager      EntityManagerInterface
     *
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        $user = new User();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() === true && $registrationForm->isValid() === true) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $registrationForm->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_task');
        }

        return $this->render(
            'registration/register.html.twig', [
                'registrationForm' => $registrationForm,
            ]
        );

    }


}
