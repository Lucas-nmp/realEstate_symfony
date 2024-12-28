<?php

namespace App\Controller;

use App\Repository\FeatureBuildingRepository;
use App\Repository\FeatureExtraRepository;
use App\Repository\PropertyRepository;
use App\Repository\FeaturePropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Finder\Finder;

class PropertyController extends AbstractController
{
    #[Route('/propertyPage/{id}', name: 'property_detail')]
    public function detail(int $id, PropertyRepository $propertyRepository,
     FeaturePropertyRepository $featurePropertyRepository,
     FeatureExtraRepository $featureExtraRepository,
     FeatureBuildingRepository $featureBuildingRepository): Response
    {
        $property = $propertyRepository->find($id);
        

        if (!$property) {
            throw $this->createNotFoundException("La propiedad no existe.");
        }

        $featuresProperty = $featurePropertyRepository->findBy(['property' => $property]);
        $featuresExtra = $featureExtraRepository->findBy(['property' => $property]);
        $featuresBuilding = $featureBuildingRepository->findBy(['property' => $property]);

        $reference = $property->getReference();

        $imageDir = 'uploads/' . $reference;
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


        return $this->render('property/index.html.twig', [
            'property' => $property, 
            'featureProperties' => $featuresProperty,
            'featureExtras' => $featuresExtra, 
            'featureBuildings' => $featuresBuilding,
            
        ]);
    }
}
