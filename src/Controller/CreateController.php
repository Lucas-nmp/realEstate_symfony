<?php

namespace App\Controller;

use App\Entity\FeatureBuilding;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Property;
use App\Entity\FeatureProperty;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



class CreateController extends AbstractController
{
    #[Route('/create', name: 'app_create', methods: ['GET', 'POST'])]
    public function createProperty(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Capturar los datos enviados en el formulario
            $title = $request->request->get('title');
            $area = $request->request->get('area');
            $price = $request->request->get('price');
            $reference = $request->request->get('reference');
            $shortDescription = $request->request->get('short_description');
            $longDescription = $request->request->get('long_description');
            $outstanding = $request->request->getBoolean('outstanding');
            $operationType = $request->request->get('operation_type');
            $observationPrice = $request->request->get('observation_price');

            // Crear la entidad Property y asignar los datos
            $property = new Property();
            $property->setTitle($title)
                ->setArea($area)
                ->setPrice((float)$price)
                ->setShortDescription($shortDescription)
                ->setReference($reference)
                ->setLongDescription($longDescription)
                ->setOutstanding($outstanding)
                ->setOperationType($operationType)
                ->setPriceObservation($observationPrice);

            // Persistir la propiedad (sin guardar aún, necesitamos el ID)
            $entityManager->persist($property);
            $entityManager->flush();

            // Subir imágenes
            $images = $request->files->get('images');
            if ($images) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $property->getReference();
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Crear la carpeta si no existe
                }

                foreach ($images as $image) {
                    if ($image->isValid() && in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                        try {
                            $newFilename = uniqid() . '.' . $image->guessExtension();
                            $image->move($uploadDir, $newFilename);
                        } catch (FileException $e) {
                            $this->addFlash('error', 'No se pudo subir la imagen: ' . $e->getMessage());
                        }
                    } else {
                        $this->addFlash('error', 'Archivo no válido: ' . $image->getClientOriginalName());
                    }
                }
            }
            /*
            // Capturar las características de la propiedad
            $property->getId();
            $bedrooms = $request->request->get('bedrooms');
            $featureProperty = new FeatureProperty();
            $featureProperty->setProperty($property)
                ->setName("Habitaciones")
                ->setQuantity($bedrooms);

            //Persistir las características de la propiedad
            $entityManager->persist($featureProperty);
            $entityManager->flush();
            */

            $numericFeaturesProperty = [
                'Habitaciones' => $request->request->get('bedrooms'),
                'Baños' => $request->request->get('bathrooms'),
                'Metros cuadrados' => $request->request->get('meters'),
                'Años antigüedad' => $request->request->get('years'),
                'Orientación' => $request->request->get('orientation'),
                'Estado de conservación' => $request->request->get('state_conservation'),
            ];

            $this->saveNumericFeatures($numericFeaturesProperty, $property, $entityManager);
    

            $checkboxFeaturesProperty = [
                'Terraza privada' => $request->request->get('terrace'),
                'Cochera' => $request->request->get('parking'),
                'Aire acondicionado' => $request->request->get('air'),
                'Calefacción' => $request->request->get('heating'),
                'Cocina amueblada' => $request->request->get('furnished_kitchen'),
                'Trastero' => $request->request->get('storage_room'),
            ];

            $this->saveCheckFeatures($checkboxFeaturesProperty, $property, $entityManager);

            $checkboxFeaturesBuilding = [
                'Parking comunitario' => $request->request->get('community_garage'),
                'Piscina comunitaria' => $request->request->get('pool')
            ];

            $this->saveCheckFeaturesBuilding($checkboxFeaturesBuilding, $property, $entityManager);

            // Redirigir después de guardar
            $this->addFlash('success', 'Propiedad guardada con éxito.');
            return $this->redirectToRoute('app_create');
        }

        return $this->render('create/index.html.twig', [
            'controller_name' => 'SavePropertyController',
        ]);
    }

    // Persistir características numéricas
    public function saveNumericFeatures(array $numericFeatures, Property $property, EntityManagerInterface $entityManager): void 
    {
        foreach ($numericFeatures as $name => $quantity) {
            $featureProperty = new FeatureProperty();
            $featureProperty->setProperty($property)
                            ->setName($name)
                            ->setQuantity($quantity);
    
            $entityManager->persist($featureProperty);
        }
    
        $entityManager->flush();
    }

    // Persistir características de tipo checkbox
    public function saveCheckFeatures(
        array $checkboxFeatures, 
        Property $property, 
        EntityManagerInterface $entityManager
    ): void {
        foreach ($checkboxFeatures as $name => $checked) {
            if ($checked) { // Solo guardar si está marcado
                $featureProperty = new FeatureProperty();
                $featureProperty->setProperty($property)
                                ->setName($name)
                                ->setQuantity(null); // Cantidad siempre null
    
                $entityManager->persist($featureProperty);
            }
        }
    
        $entityManager->flush();
    }

    public function saveCheckFeaturesBuilding(
        array $checkboxFeatures, 
        Property $property, 
        EntityManagerInterface $entityManager
    ): void {
        foreach ($checkboxFeatures as $name => $checked) {
            if ($checked) { // Solo guardar si está marcado
                $featureProperty = new FeatureBuilding();
                $featureProperty->setProperty($property)
                                ->setName($name);
                                
                $entityManager->persist($featureProperty);
            }
        }
    
        $entityManager->flush();
    }

    

}
