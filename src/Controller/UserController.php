<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Repository\UserRepository;



class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager,SessionInterface $session): Response
    //objet  $request pour les request, instance em=pour interagir avec la bdd
    {   
            // on a ajouté pour la sseion
        $session->set('user', $this->getUser());
        //objet $user contenant les données de l'utilisateur fournis par le formulaire
        $user = new User();
        //création du formulaire avc la class type de formulaire UserFormType et l'associe à l'objet $user. Le formulaire est ensuite utilisé pour gérer les données d'entrée de l'utilisateur.
        $form = $this->createForm(UserFormType::class, $user);
        // raite la soumission du formulaire. 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    // encode the plain password
        $user->setPlainPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
    
        $entityManager->persist($user);
        $entityManager->flush();
        // Rediriger vers la page de connexion après l'inscription
        return $this->redirectToRoute('app_login');
    }
    
        return $this->render('user/user.html.twig', [
        'form' => $form->createView(),
    ]);

    }

    
    #[Route('/userliste', name: 'app_userliste')]
    public function userListe(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();
        return $this->render('user/userliste.html.twig', [
            'controller_name' => 'UserController',
             'user' => $user,
        ]);
    }

}
