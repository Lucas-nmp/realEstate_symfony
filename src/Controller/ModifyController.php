<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ModifyController extends AbstractController
{
    #[Route('/modify', name: 'app_modify')]
    public function index(): Response
    {
        return $this->render('modify/index.html.twig', [
            'controller_name' => 'ModifyController',
        ]);
    }
}
