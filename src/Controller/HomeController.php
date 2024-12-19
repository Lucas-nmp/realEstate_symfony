<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Property;



class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //$users = $entityManager->getRepository(User::class)->findAll();
        $properties = $entityManager->getRepository(Property::class)->findAll();

        return $this->render('home/index.html.twig', [
            'properties' => $properties,
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/home/registerJ', name: 'register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $property = new Property();
        $property->setReference($data['reference'] ?? null);
        $property->setArea($data['area'] ?? null);
        $property->setLongDescription($data['long_description'] ?? null);
        $property->setOperationType($data['operation_type'] ?? null);
        $property->setPrice($data['price'] ?? null);
        $property->setShortDescription($data['short_description'] ?? null);
        $property->setPriceObservation($data['observation_price'] ?? null);
        $property->setTitle($data['title'] ?? null);
        $property->setOutstanding($data['outstanding'] ?? false);

        $entityManager->persist($property);
        $entityManager->flush();

        return $this->json(['message' => 'Property registered successfully'], 201);
    }
}
