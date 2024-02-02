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
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

      #[Route('/editpermis/{id}', name: 'app_edit_permis')]
    public function editPermis(Request $request, Permis $permis, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PermisFormType::class, $permis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $uploadedFile = $form->get('image')->getData();
                if ($uploadedFile) {
                    $newFilename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();

                    $uploadedFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                    $permis->setImage($newFilename);
                }

                $entityManager->flush();

                return $this->redirectToRoute('app_permisliste');
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur s\'est produite lors de la mise à jour du fichier.');
            }
        }

        return $this->render('permis/editpermis.html.twig', [
            'controller_name' => 'PermisController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/updatepermis/{id}', name: 'app_update_permis')]
    public function updatePermis(Request $request, Permis $permis): Response
    {
        $form = $this->createForm(PermisFormType::class, $permis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $uploadedFile = $form->get('image')->getData();
                if ($uploadedFile) {
                    $newFilename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();

                    $uploadedFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                    $permis->setImage($newFilename);
                }

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('app_permisliste');
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur s\'est produite lors de la mise à jour du fichier.');
            }
        }

        return $this->render('permis/updatepermis.html.twig', [
            'controller_name' => 'PermisController',
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/deletepermis/{id}', name: 'app_permis_delete')]
    public function deletePermis(Request $request, PermisRepository $permis, EntityManagerInterface $entityManager,$id): Response
    {
           $deletePermis = $permis->find($id);
    
        if($deletePermis){
            $entityManager->remove($deletePermis);
            $entityManager->flush();
        }
    
    
        return $this->redirectToRoute('app_permisliste', [], Response::HTTP_SEE_OTHER);
    }

}
