<?php

namespace App\Controller;

use App\Entity\Permis;
use App\Form\PermisFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PermisRepository;

class PermisController extends AbstractController
{
    #[Route('/permis', name: 'app_permis')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $permis = new Permis();
        $form = $this->createForm(PermisFormType::class, $permis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('image')->getData();
            $newFilename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();

            // Déplacez le fichier vers le répertoire où vous souhaitez le stocker
            $uploadedFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );

            // Enregistrez le nom du fichier dans l'entité
            $permis->setImage($newFilename);

            $entityManager->persist($permis);
            $entityManager->flush();

            // Rediriger ou effectuer d'autres actions après la soumission réussie
            return $this->redirectToRoute('app_permis');
        }

        return $this->render('permis/permis.html.twig', [
            'controller_name' => 'PermisController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/permisliste', name: 'app_permisliste')]
    public function permisliste(PermisRepository $permisRepository): Response
    {
        $permis = $permisRepository->findAll();

        return $this->render('permis/permisliste.html.twig', [
            'controller_name' => 'PermisController',
            'permis' => $permis,
        ]);
    }
}
