<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Property;

class ModifyController extends AbstractController
{
    #[Route('/modify', name: 'app_modify', methods: ['GET', 'POST'])]
    public function modify(EntityManagerInterface $entityManager): Response
    {


        $properties = $entityManager->getRepository(Property::class)->findAll();
        
        return $this->render('modify/index.html.twig', [
            'properties' => $properties,
            
        ]);
    }

    #[Route('/property/{id}', name: 'app_get_property', methods: ['GET'])]
    public function getProperty(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $property = $entityManager->getRepository(Property::class)->find($id);

        if (!$property) {
            return new JsonResponse(['error' => 'Property not found'], 404);
        }

        return new JsonResponse([
            'id' => $property->getId(),
            'title' => $property->getTitle(),
            'area' => $property->getArea(),
            'price' => $property->getPrice(),
            'reference' => $property->getReference(),
            'reference' => $property->getReference(),
            'short_description' => $property->getShortDescription(),
            'long_description' => $property->getLongDescription(),
            'outstanding' => $property->isOutstanding(),
            'operation_type' => $property->getOperationType(),
            'observation_price' => $property->getPriceObservation(),
        ]);
    }

    #[Route('/modify/property', name: 'app_modify_property', methods: ['POST'])]
    public function modifyProperty(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->request->get('property_id');
        $title = $request->request->get('title');
        $area = $request->request->get('area');
        $reference = $request->request->get('reference');
        $price = $request->request->get('price');
        $observationPrice = $request->request->get('observation_price');
        $reference = $request->request->get('reference');
        $shortDescription = $request->request->get('short_description');
        $longDescription = $request->request->get('long_description');

        $property = $entityManager->getRepository(Property::class)->find($id);

        if (!$property) {
            $this->addFlash('error', 'Propiedad no encontrada');
            return $this->redirectToRoute('app_modify');
        }

        $property->setTitle($title)
                ->setArea($area)
                ->setPrice((float) $price)
                ->setReference($reference)
                ->setShortDescription($shortDescription)
                ->setPriceObservation($observationPrice)
                ->setLongDescription($longDescription);

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

        $this->addFlash('success', 'Propiedad modificada correctamente!');
        return $this->redirectToRoute('app_modify');
    }


}