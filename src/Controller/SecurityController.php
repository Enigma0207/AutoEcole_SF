<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($this->getUser()) {
            $userRoles = $this->getUser()->getRoles();

            // Redirection en fonction du rôle
            if (in_array('ROLE_ELEVE', $userRoles)) {
                return $this->redirectToRoute('app_permisliste');
            } elseif (in_array('ROLE_MONITEUR', $userRoles)) {
                return $this->redirectToRoute('app_permis');
            } elseif (in_array('ROLE_ADMIN', $userRoles)) {
                return $this->redirectToRoute('app_userliste', ['firstname' => $this->getUser()->getFirstname()]);
            }

            // Ajoutez d'autres redirections en fonction des rôles au besoin

            // Par défaut, redirige vers une page par défaut
            return $this->redirectToRoute('app_home');
        }

        return $this->render('login/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de votre pare-feu.');
    }
}
