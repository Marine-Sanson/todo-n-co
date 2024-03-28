<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{


    /**
     * Summary of index
     *
     * @return Response
     */
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {

        return $this->render('default/index.html.twig');

    }


}
