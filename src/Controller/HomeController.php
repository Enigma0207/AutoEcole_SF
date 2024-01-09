<?php

namespace App\Controller;
use App\Entity\Permis;
use App\Form\PermisFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PermisRepository;


class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(PermisRepository $permisRepository): Response
    {  
        $permis = $permisRepository->findAll();
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
             'permis' => $permis,
        ]);
    }

    #[Route('/home/{id}', name: 'app_detailMoto')]
    public function detailMoto(PermisRepository $permisRepository, $id): Response
    { 
         $permis = $permisRepository->find($id);
        return $this->render('home/detailMoto.html.twig', [
            'permis' => $permis,
        ]);
    }
}
