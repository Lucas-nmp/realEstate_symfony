<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Property;
use Symfony\Component\Finder\Finder;



class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //$users = $entityManager->getRepository(User::class)->findAll();
        $properties = $entityManager->getRepository(Property::class)->findAll();

        $saleproperties = [];
        $rentproperties = [];
        $vacationalproperties = [];
        $outstandingProperties = [];

        foreach ($properties as $property) {
            $imageDir = 'uploads/' . $property->getReference();
            $finder = new Finder();

            // Agregar imágenes a cada objeto property
            if (is_dir($imageDir)) {
                $finder->files()->in($imageDir)->name('/\\.(jpg|jpeg|png)$/i');
                $images = [];

                foreach ($finder as $file) {
                    $images[] = $file->getRelativePathname();
                }

                $property->images = $images;
            } else {
                $property->images = [];
            }

            // Filtrar por operacion y si es destacada
            switch ($property->getOperationType()) {
                case 'Venta':
                    $saleproperties[] = $property;
                    if ($property->isOutstanding()) {
                        $outstandingProperties[] = $property;
                    }
                    break;
                    case 'Alquiler':
                        $rentproperties[] = $property;
                        if ($property->isOutstanding()) {
                            $outstandingProperties[] = $property;
                        }
                        break;
                    case 'Alquiler Vacacional':
                        $vacationalproperties[] = $property;
                        if ($property->isOutstanding()) {
                            $outstandingProperties[] = $property;
                        }
                        break;
            }
        }

        return $this->render('home/index.html.twig', [
            'properties' => $properties,
            'saleproperties' => $saleproperties,
            'rentproperties' => $rentproperties,
            'vacationalproperties' => $vacationalproperties,
            'outstandingProperties' => $outstandingProperties,
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
