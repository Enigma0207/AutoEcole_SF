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
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SessionInterface $session, Security $security): Response
    {
        $session->set('user', $this->getUser());
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPlainPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

                // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
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
