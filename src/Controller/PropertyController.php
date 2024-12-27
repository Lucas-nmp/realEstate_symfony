<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PropertyController extends AbstractController
{
    #[Route('/propertyPage/{id}', name: 'property_detail')]
    public function detail(int $id, PropertyRepository $propertyRepository): Response
    {
        $property = $propertyRepository->find($id);

        if (!$property) {
            throw $this->createNotFoundException("La propiedad no existe.");
        }
        return $this->render('property/index.html.twig', [
            'property' => $property, 
        ]);
    }
}
