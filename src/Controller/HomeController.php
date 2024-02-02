<?php

namespace App\Controller;
use App\Entity\Permis;
use App\Form\PermisFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PermisRepository;
// use Symfony\Component\HttpFoundation\RedirectResponse;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PermisRepository $permisRepository): Response
    {  
        $permis = $permisRepository->findAll();
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
             'permis' => $permis,
        ]);
    }

    #[Route('/detailPermis/{id}', name: 'app_detail')]
     public function detailMoto(PermisRepository $permisRepository, $id): Response
     { 
         $permis = $permisRepository->find($id);
     
         if (!$permis) {
             throw $this->createNotFoundException('Permis not found for id ' . $id);
         }
     
         return $this->render('home/detail.html.twig', [
             'permis' => $permis,
         ]);
     }
    
    //  #[Route('/redirect-to-footer', name: 'app_redirect_to_footer')]
    // public function redirectToFooter(): RedirectResponse
    // {
    //     // Redirection vers la route associée à la page footer.html.twig
    //     return $this->redirectToRoute('footer.html.twig');
    // }
     
}
