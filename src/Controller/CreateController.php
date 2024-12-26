<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Property;
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

            // Redirigir después de guardar
            $this->addFlash('success', 'Propiedad guardada con éxito.');
            return $this->redirectToRoute('app_create');
        }

        return $this->render('create/index.html.twig', [
            'controller_name' => 'SavePropertyController',
        ]);
    }
}
