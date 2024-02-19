<?php

namespace App\Controller;

use App\Service\Cart;
use App\Entity\Creneaux;
use App\Form\CreneauxType;
use App\Repository\UserRepository;
use App\Repository\CreneauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/creneaux')]
class CreneauxController extends AbstractController
{   

    #[Route('/', name: 'app_creneaux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {

        $moniteur = $userRepository->getUsersByRole('ROLE_MONITEUR');
        $eleve = $userRepository->getUsersByRole('ROLE_ELEVE');
        // dd($moniteur);
        $creneaux = new Creneaux();
        $form = $this->createForm(CreneauxType::class, $creneaux, ["moniteur" => $moniteur, "eleve"=>$eleve]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($creneaux);
            $entityManager->flush();

            return $this->redirectToRoute('app_creneaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creneaux/new.html.twig', [
            'creneaux' => $creneaux,
            'form' => $form,
        ]);
    }

    #[Route('/list', name: 'app_creneaux_index', methods: ['GET'])]
    public function index(CreneauxRepository $creneauxRepository): Response
    {
        // $creneauxes = $creneauxRepository->findAll();
        return $this->render('creneaux/list.html.twig', [
            'creneauxes' => $creneauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_creneaux_show', methods: ['GET'])]
    public function show(Creneaux $creneaux): Response
    { 
        return $this->render('creneaux/show.html.twig', [
            'creneaux' => $creneaux,
        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_creneaux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Creneaux $creneaux, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreneauxType::class, $creneaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_creneaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creneaux/edit.html.twig', [
            'creneaux' => $creneaux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creneaux_delete', methods: ['POST'])]
    public function delete(Request $request, Creneaux $creneaux, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$creneaux->getId(), $request->request->get('_token'))) {
            $entityManager->remove($creneaux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_creneaux_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/update', name: 'app_creneaux_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Creneaux $creneaux, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreneauxType::class, $creneaux);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            // Ajoutez une instruction dump pour déboguer
            dump('Redirection effectuée');
    
            return $this->redirectToRoute('app_creneaux_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('creneaux/updateCreneaux.html.twig', [
            'creneaux' => $creneaux,
            'form' => $form,
        ]);
    }

    #[Route('/creneaux/{id}/reserve', name: 'app_reserve_creneaux', methods: ['GET'])]
    public function reserveCreneaux(Creneaux $creneaux, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez si la plage horaire est disponible
        if ($creneaux->isIsAvailable()) {
            // Mettez à jour la plage horaire comme réservée
            $creneaux->setIsAvailable(false);
    
            // Définissez l'élève qui a réservé la plage horaire 
            $user = $this->getUser(); // Supposons que vous avez implémenté l'authentification de l'utilisateur
            $creneaux->setUserEleve($user);
    
            $entityManager->flush();
    
            // Redirigez vers la page de la liste ou toute autre page appropriée
            return $this->redirectToRoute('app_creneaux_index');
        }
    
        // Gérez le cas où la plage horaire est déjà réservée
        // Vous voudrez peut-être personnaliser cette partie en fonction de vos besoins
        $this->addFlash('error', 'Cette plage horaire est déjà réservée.');
    
        // Redirigez vers la page de la liste ou toute autre page appropriée
        return $this->redirectToRoute('app_creneaux_index');
    }

    #[Route('/creneaux/validate', name: 'app_creneaux_validate', methods: ['GET'])]
    public function validateCreneaux(EntityManagerInterface $entityManager, Cart $cart): Response
    {
        $cartCreneaux = $cart->getDetails();
        $creneaux = $cartCreneaux['creneaux'];
        
        foreach($creneaux as $infoCreneau){
            $creneau = $infoCreneau['creneau'];
            // Vérifiez si la plage horaire est disponible
            if ($creneau->isIsAvailable()) {
                // Mettez à jour la plage horaire comme réservée
                $creneau->setIsAvailable(false);
        
                // Définissez l'élève qui a réservé la plage horaire 
                $user = $this->getUser(); // Supposons que vous avez implémenté l'authentification de l'utilisateur
                $creneau->setUserEleve($user);
                $entityManager->persist($creneau);        
            }     
        }

        $entityManager->flush();
        $cart->remove();
        $this->addFlash('success', 'Vos créneaux sont validés.'); 
         
        // Redirigez vers la page de la liste ou toute autre page appropriée
        return $this->redirectToRoute('app_creneaux_index'); 
    }

    #[Route('/creneaux/{id}/cancel', name: 'app_cancel', methods: ['GET'])]
    public function cancel(Creneaux $creneaux, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez si l'utilisateur connecté a réservé cette plage horaire
        $user = $this->getUser();
        if ($user && $creneaux->getUserEleve() === $user) {
            // Annulez la réservation en mettant à jour la plage horaire comme disponible
            $creneaux->setIsAvailable(true);
            $creneaux->setUserEleve(null); // Définissez l'élève comme null
    
            $entityManager->flush();
    
            // Redirigez vers la page de la liste ou toute autre page appropriée
            return $this->redirectToRoute('app_creneaux_index');
        }
    
        // Gérez le cas où l'utilisateur n'est pas autorisé à annuler cette réservation
        // Vous pouvez personnaliser cette partie en fonction de vos besoins
        $this->addFlash('error', 'Vous n\'êtes pas autorisé à annuler cette réservation.');
    
        // Redirigez vers la page de la liste ou toute autre page appropriée
        return $this->redirectToRoute('app_creneaux_index');
    }
}




   