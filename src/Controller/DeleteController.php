<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Property;

class DeleteController extends AbstractController
{
    #[Route('/delete', name: 'app_delete', methods: ['GET', 'POST'])]
    public function delete(EntityManagerInterface $entityManager): Response
    {

        $properties = $entityManager->getRepository(Property::class)->findAll();

        return $this->render('delete/index.html.twig', [
            'properties' => $properties,
            
        ]);
    }

    #[Route('/delete/property', name: 'app_delete_property', methods: ['POST'])]
    public function deleteProperty(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->request->get('property_id');

        $property = $entityManager->getRepository(Property::class)->find($id);

        if (!$property) {
            $this->addFlash('error', 'Propiedad no encontrada');
            return $this->redirectToRoute('app_delete');
        }

        $reference = $property->getReference();
        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $reference;

        $entityManager->remove($property);
        $entityManager->flush();

        if (is_dir($uploadDir)) {
            $this->deleteDirectory($uploadDir);
        }

        $this->addFlash('success', 'Propiedad eliminada correctamente!');
        return $this->redirectToRoute('app_delete');
    }

    private function deleteDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $files = array_diff(scandir($directory), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $directory . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath); // Llamada recursiva
            } else {
                unlink($filePath); // Elimina el archivo
            }
        }

        rmdir($directory); // Finalmente elimina la carpeta
    }
}

